<?php

namespace Ssh521\KoreanBbs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Ssh521\KoreanBbs\Models\Board;
use Ssh521\KoreanBbs\Models\BoardGroup;
use Ssh521\KoreanBbs\Models\Comment;
use Ssh521\KoreanBbs\Models\Post;

class KoreanBbsTestSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $community = BoardGroup::updateOrCreate(
            ['slug' => 'community'],
            ['name' => '커뮤니티', 'order' => 10]
        );

        $support = BoardGroup::updateOrCreate(
            ['slug' => 'support'],
            ['name' => '고객지원', 'order' => 20]
        );

        $boards = [
            [
                'group_id' => $community->id,
                'name' => '자유게시판',
                'slug' => 'free',
                'description' => '목록형 스킨 테스트용 게시판입니다.',
                'skin' => 'list',
                'width' => 'max-w-6xl',
                'order' => 10,
            ],
            [
                'group_id' => $community->id,
                'name' => '갤러리',
                'slug' => 'gallery',
                'description' => '갤러리형 스킨 테스트용 게시판입니다.',
                'skin' => 'gallery',
                'width' => 'max-w-6xl',
                'order' => 20,
            ],
            [
                'group_id' => $support->id,
                'name' => '문의게시판',
                'slug' => 'qna',
                'description' => '비밀글과 댓글을 확인하기 위한 문의 게시판입니다.',
                'skin' => 'list',
                'width' => '900px',
                'allow_secret' => true,
                'order' => 30,
            ],
            [
                'group_id' => null,
                'name' => '커스텀 스킨 테스트',
                'slug' => 'custom-test',
                'description' => 'custom 스킨 확인용 게시판입니다.',
                'skin' => 'custom',
                'width' => '',
                'order' => 40,
            ],
        ];

        foreach ($boards as $index => $boardData) {
            $board = Board::updateOrCreate(
                ['slug' => $boardData['slug']],
                array_merge([
                    'write_level' => 0,
                    'comment_level' => 0,
                    'file_level' => 0,
                    'posts_per_page' => 10,
                    'allow_secret' => false,
                    'use_comment' => true,
                    'use_like' => true,
                    'use_file' => true,
                    'is_active' => true,
                ], $boardData)
            );

            $this->seedPosts($board, $index, $now);
        }
    }

    private function seedPosts(Board $board, int $boardIndex, Carbon $now): void
    {
        $password = Hash::make('password');

        $notice = Post::updateOrCreate(
            ['board_id' => $board->id, 'title' => "[공지] {$board->name} 이용 안내"],
            [
                'author_name' => '관리자',
                'author_password' => $password,
                'content' => "{$board->name} 테스트 게시판입니다.\n\n목록, 상세, 댓글, 삭제 확인 흐름을 점검해보세요.",
                'is_notice' => true,
                'is_secret' => false,
                'view_count' => 120 + $boardIndex,
                'like_count' => 3,
                'dislike_count' => 0,
            ]
        );
        $noticeDate = $now->copy()->subDays(20 - $boardIndex);
        $notice->forceFill(['created_at' => $noticeDate, 'updated_at' => $noticeDate])->save();

        for ($i = 1; $i <= 12; $i++) {
            $post = Post::updateOrCreate(
                ['board_id' => $board->id, 'title' => "{$board->name} 테스트 글 {$i}"],
                [
                    'author_name' => '테스터' . (($i % 4) + 1),
                    'author_password' => $password,
                    'content' => "테스트 본문 {$i}입니다.\n\n스킨 출력, 검색, 페이지네이션, 댓글 표시를 확인하기 위한 샘플 데이터입니다.",
                    'is_notice' => false,
                    'is_secret' => $board->allow_secret && $i % 5 === 0,
                    'view_count' => ($boardIndex + 1) * 10 + $i,
                    'like_count' => $i % 4,
                    'dislike_count' => $i % 3 === 0 ? 1 : 0,
                ]
            );
            $postDate = $now->copy()->subDays(12 - $i)->subHours($boardIndex);
            $post->forceFill(['created_at' => $postDate, 'updated_at' => $postDate])->save();

            if ($i <= 6 && $board->use_comment) {
                $this->seedComments($post, $password, $now->copy()->subDays(6 - $i));
            }
        }
    }

    private function seedComments(Post $post, string $password, Carbon $createdAt): void
    {
        $comment = Comment::updateOrCreate(
            ['post_id' => $post->id, 'parent_id' => null, 'content' => '샘플 댓글입니다. 화면에서 댓글 표시를 확인하세요.'],
            [
                'author_name' => '댓글러',
                'author_password' => $password,
                'like_count' => 1,
                'dislike_count' => 0,
            ]
        );
        $comment->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();

        $reply = Comment::updateOrCreate(
            ['post_id' => $post->id, 'parent_id' => $comment->id, 'content' => '샘플 답글입니다. 대댓글 UI 확인용입니다.'],
            [
                'author_name' => '답글러',
                'author_password' => $password,
                'like_count' => 0,
                'dislike_count' => 0,
            ]
        );
        $replyDate = $createdAt->copy()->addMinutes(10);
        $reply->forceFill(['created_at' => $replyDate, 'updated_at' => $replyDate])->save();
    }
}
