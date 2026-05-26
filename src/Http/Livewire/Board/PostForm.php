<?php

namespace Ssh521\KoreanBbs\Http\Livewire\Board;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Ssh521\KoreanBbs\EditorResolver;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\BbsFile;
use Ssh521\KoreanBbs\Models\Post;
use Ssh521\KoreanBbs\SkinResolver;
use Ssh521\KoreanBbs\Support\ContentSanitizer;

class PostForm extends Component
{
    use WithFileUploads;

    public Board $board;
    public ?Post $post = null;

    public string $title = '';
    public string $content = '';
    public bool $isNotice = false;
    public bool $isSecret = false;
    public string $authorName = '';
    public string $authorPassword = '';
    public bool $captchaEnabled = false;
    public string $captchaProvider = 'math';
    public string $captchaQuestion = '';
    public string $captchaAnswer = '';
    public string $captchaToken = '';

    public array $uploadedFiles = [];

    public function mount(string $boardSlug, ?Post $post = null): void
    {
        $this->board = Board::where('slug', $boardSlug)->where('is_active', true)->firstOrFail();

        if ($post && $post->exists) {
            $this->post    = $post;
            $this->title   = $post->title;
            $this->content = $post->content;
            $this->isNotice = $post->is_notice;
            $this->isSecret = $post->is_secret;
            $this->authorName = $post->author_name ?? '';
        }

        $this->captchaEnabled = $this->shouldUseCaptcha();
        $this->captchaProvider = $this->captchaProvider();

        if ($this->captchaEnabled && $this->captchaProvider === 'math') {
            $this->refreshCaptcha();
        }
    }

    public function save(): void
    {
        $captchaRequired = $this->shouldUseCaptcha();

        $rules = [
            'title'         => 'required|string|max:200',
            'content'       => 'required|string',
            'uploadedFiles' => 'nullable|array',
            'uploadedFiles.*' => 'file|max:' . config('korean-bbs.upload.max_size'),
        ];

        if (!auth()->check() && $this->post) {
            $rules['authorName']     = 'required|string|max:20';
            $rules['authorPassword'] = 'required|string|min:4|max:20';
        } elseif (!auth()->check()) {
            $rules['authorName']     = 'required|string|max:20';
            $rules['authorPassword'] = 'required|string|min:4|max:20';
        }

        if ($captchaRequired) {
            if (in_array($this->captchaProvider(), ['turnstile', 'recaptcha'], true)) {
                $rules['captchaToken'] = 'required|string';
            } else {
                $rules['captchaAnswer'] = 'required|string';
            }
        }

        $this->validate($rules, [
            'captchaAnswer.required' => '자동등록 방지 답을 입력하세요.',
            'captchaToken.required' => '자동등록 방지를 완료하세요.',
        ]);

        if ($captchaRequired && !$this->captchaIsValid()) {
            $field = in_array($this->captchaProvider(), ['turnstile', 'recaptcha'], true) ? 'captchaToken' : 'captchaAnswer';
            $message = in_array($this->captchaProvider(), ['turnstile', 'recaptcha'], true)
                ? '자동등록 방지 검증에 실패했습니다. 다시 시도하세요.'
                : '자동등록 방지 답이 올바르지 않습니다.';

            $this->addError($field, $message);
            $this->captchaAnswer = '';
            $this->captchaToken = '';

            if ($this->captchaProvider() === 'math') {
                $this->refreshCaptcha();
            } elseif ($this->captchaProvider() === 'turnstile') {
                $this->dispatch('korean-bbs-turnstile-reset');
            } else {
                $this->dispatch('korean-bbs-recaptcha-reset');
            }

            return;
        }

        if (!auth()->check() && $this->post && !Hash::check($this->authorPassword, $this->post->getRawOriginal('author_password'))) {
            $this->addError('authorPassword', '비밀번호가 올바르지 않습니다.');
            return;
        }

        $this->content = ContentSanitizer::clean($this->content);

        if (trim(strip_tags($this->content)) === '') {
            $this->addError('content', '내용을 입력하세요.');
            return;
        }

        $data = [
            'board_id'  => $this->board->id,
            'title'     => $this->title,
            'content'   => $this->content,
            'is_notice' => session('bbs_admin_authenticated') && $this->isNotice,
            'is_secret' => $this->board->allow_secret && $this->isSecret,
        ];

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        } else {
            $data['author_name']     = $this->authorName;

            if (!$this->post) {
                $data['author_password'] = Hash::make($this->authorPassword);
            }
        }

        if ($this->post) {
            $this->post->update($data);
            $savedPost = $this->post;
        } else {
            $savedPost = Post::create($data);
        }

        $this->handleFileUploads($savedPost);
        session()->forget($this->captchaSessionKey());

        $this->redirect(route('bbs.posts.show', [$this->board->slug, $savedPost->id]));
    }

    public function refreshCaptcha(): void
    {
        $min = (int) config('korean-bbs.captcha.min', 1);
        $max = (int) config('korean-bbs.captcha.max', 9);

        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }

        $left = random_int($min, $max);
        $right = random_int($min, $max);

        $this->captchaQuestion = "{$left} + {$right}";
        session()->put($this->captchaSessionKey(), (string) ($left + $right));
    }

    private function handleFileUploads(Post $post): void
    {
        if (empty($this->uploadedFiles)) {
            return;
        }

        $disk        = config('korean-bbs.upload.disk');
        $basePath    = config('korean-bbs.upload.path');
        $imageTypes  = config('korean-bbs.upload.image_types');
        $allowedExts = config('korean-bbs.upload.allowed_types');

        foreach ($this->uploadedFiles as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedExts)) {
                continue;
            }

            $storedName = $file->store($basePath . '/' . $post->board_id, $disk);
            $isImage    = in_array($ext, $imageTypes);

            BbsFile::create([
                'post_id'       => $post->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_name'   => basename($storedName),
                'path'          => $storedName,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'is_image'      => $isImage,
            ]);

            // 갤러리형 게시판 썸네일 설정
            if ($isImage && !$post->thumbnail_path) {
                $post->update(['thumbnail_path' => $storedName]);
            }
        }
    }

    private function shouldUseCaptcha(): bool
    {
        if (!config('korean-bbs.captcha.enabled', true)) {
            return false;
        }

        if (!config('korean-bbs.captcha.guest_only', true)) {
            return true;
        }

        return !auth()->check() && !session('bbs_admin_authenticated');
    }

    private function captchaIsValid(): bool
    {
        if ($this->captchaProvider() === 'turnstile') {
            return $this->turnstileIsValid();
        }

        if ($this->captchaProvider() === 'recaptcha') {
            return $this->recaptchaIsValid();
        }

        $expected = session($this->captchaSessionKey());

        return is_string($expected) && hash_equals($expected, trim($this->captchaAnswer));
    }

    private function captchaSessionKey(): string
    {
        return 'korean-bbs.post-form-captcha.' . $this->board->id . '.' . ($this->post?->id ?? 'new');
    }

    private function captchaProvider(): string
    {
        $provider = config('korean-bbs.captcha.provider', 'math');

        if (!in_array($provider, ['turnstile', 'recaptcha'], true)) {
            return 'math';
        }

        if (!config("korean-bbs.captcha.{$provider}.site_key") || !config("korean-bbs.captcha.{$provider}.secret_key")) {
            return 'math';
        }

        return $provider;
    }

    private function turnstileIsValid(): bool
    {
        $secretKey = config('korean-bbs.captcha.turnstile.secret_key');

        if (!$secretKey || trim($this->captchaToken) === '') {
            return false;
        }

        $response = $this->postTurnstileVerification($secretKey, $this->captchaToken);

        return is_array($response) && ($response['success'] ?? false) === true;
    }

    private function recaptchaIsValid(): bool
    {
        $secretKey = config('korean-bbs.captcha.recaptcha.secret_key');

        if (!$secretKey || trim($this->captchaToken) === '') {
            return false;
        }

        $response = $this->postRecaptchaVerification($secretKey, $this->captchaToken);

        return is_array($response) && ($response['success'] ?? false) === true;
    }

    private function postTurnstileVerification(string $secretKey, string $token): ?array
    {
        $payload = http_build_query([
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        if (function_exists('curl_init')) {
            $curl = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
            curl_setopt_array($curl, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            ]);

            $body = curl_exec($curl);
            curl_close($curl);
        } else {
            $body = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $payload,
                    'timeout' => 5,
                ],
            ]));
        }

        if (!is_string($body) || $body === '') {
            return null;
        }

        $decoded = json_decode($body, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function postRecaptchaVerification(string $secretKey, string $token): ?array
    {
        $payload = http_build_query([
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        if (function_exists('curl_init')) {
            $curl = curl_init('https://www.google.com/recaptcha/api/siteverify');
            curl_setopt_array($curl, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            ]);

            $body = curl_exec($curl);
            curl_close($curl);
        } else {
            $body = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $payload,
                    'timeout' => 5,
                ],
            ]));
        }

        if (!is_string($body) || $body === '') {
            return null;
        }

        $decoded = json_decode($body, true);

        return is_array($decoded) ? $decoded : null;
    }

    public function render()
    {
        $label = $this->post ? '게시글 수정' : '게시글 작성';

        return view(SkinResolver::resolve($this->board->skin, 'form'))
            ->with('editorView', EditorResolver::resolve($this->board->skin))
            ->layout(config('korean-bbs.layout'), [
                'title' => "{$this->board->name} - {$label}",
                'board' => $this->board,
                'post' => $this->post,
                'breadcrumbs' => [
                    ['label' => '게시판', 'url' => route('bbs.index')],
                    ['label' => $this->board->name, 'url' => route('bbs.posts.index', $this->board->slug)],
                    ['label' => $label],
                ],
            ]);
    }
}
