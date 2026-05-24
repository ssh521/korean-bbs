@once
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.13/dist/trix.css">
    <script src="https://unpkg.com/trix@2.1.13/dist/trix.umd.min.js"></script>
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        trix-editor.korean-bbs-trix {
            min-height: 18rem;
        }

        trix-editor.korean-bbs-trix blockquote {
            margin: 1rem 0;
            padding: 0.75rem 1rem;
            border-left: 4px solid #d1d5db;
            background: #f9fafb;
            color: #4b5563;
        }

        trix-editor.korean-bbs-trix ul,
        trix-editor.korean-bbs-trix ol {
            margin: 0.75rem 0;
            padding-left: 1.5rem;
        }

        trix-editor.korean-bbs-trix ul {
            list-style: disc;
        }

        trix-editor.korean-bbs-trix ol {
            list-style: decimal;
        }

        trix-editor.korean-bbs-trix li {
            display: list-item;
            margin: 0.25rem 0;
        }

        trix-editor.korean-bbs-trix pre {
            margin: 1rem 0;
            padding: 1rem;
            overflow-x: auto;
            border-radius: 0.5rem;
            background: #111827;
            color: #f9fafb;
        }
    </style>
@endonce

@php($editorId = 'korean-bbs-editor-' . uniqid())
@php($allowSourceView = config('korean-bbs.editors.allow_source_view', true))

<div wire:ignore
     x-data="{
        value: @entangle('content').live,
        sourceMode: false,
        fixToolbarButtons() {
            const toolbar = this.$refs.editor?.toolbarElement;
            if (toolbar) {
                toolbar.querySelectorAll('button').forEach(button => button.type = 'button');
            }
        }
     }"
     x-init="
        $refs.input.value = value || '';
        $refs.editor.editor.loadHTML(value || '');
        fixToolbarButtons();
        $nextTick(() => fixToolbarButtons());
        $refs.editor.addEventListener('trix-initialize', () => fixToolbarButtons());
        $refs.editor.addEventListener('trix-change', () => value = $refs.input.value);
        $refs.editor.addEventListener('trix-file-accept', event => event.preventDefault());
        $watch('value', next => {
            if ($refs.input.value !== (next || '')) {
                $refs.input.value = next || '';
                $refs.editor.editor.loadHTML(next || '');
            }
        });
     ">
    @if($allowSourceView)
        <div class="mb-2 flex justify-end">
            <button type="button"
                    class="text-xs font-medium text-gray-600 border border-gray-300 rounded px-3 py-1.5 hover:bg-gray-50"
                    x-text="sourceMode ? '에디터 보기' : 'HTML 소스'"
                    x-on:click="
                        sourceMode = !sourceMode;
                        if (!sourceMode) {
                            $refs.input.value = value || '';
                            $refs.editor.editor.loadHTML(value || '');
                        }
                    "></button>
        </div>
    @endif

    <input id="{{ $editorId }}" type="hidden" x-ref="input">
    <trix-editor input="{{ $editorId }}"
                 x-ref="editor"
                 x-show="!sourceMode"
                 class="korean-bbs-trix bg-white border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 @error('content') border-red-400 @enderror"
                 placeholder="{{ $placeholder ?? '내용을 입력하세요' }}"></trix-editor>

    @if($allowSourceView)
        <textarea x-show="sourceMode"
                  x-model="value"
                  rows="{{ $rows ?? 12 }}"
                  spellcheck="false"
                  class="w-full min-h-72 border border-gray-300 rounded-lg px-3 py-2.5 font-mono text-xs resize-y focus:outline-none focus:ring-1 focus:ring-blue-400 @error('content') border-red-400 @enderror"
                  placeholder="<p>HTML 소스를 입력하세요</p>"></textarea>
    @endif
</div>
