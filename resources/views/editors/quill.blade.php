@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css">
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <style>
        .korean-bbs-quill .ql-editor {
            min-height: 18rem;
            font-size: 0.875rem;
            line-height: 1.625;
        }

        .korean-bbs-quill .ql-toolbar {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .korean-bbs-quill .ql-container {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border-color: #d1d5db;
            font-family: inherit;
        }
    </style>
@endonce

@php($allowSourceView = config('korean-bbs.editors.allow_source_view', true))

<div wire:ignore
     x-data="{
        value: @entangle('content').live,
        sourceMode: false,
        editor: null,
        syncFromEditor() {
            if (this.editor) {
                this.value = this.editor.getSemanticHTML();
            }
        },
        syncToEditor() {
            if (this.editor && this.editor.getSemanticHTML() !== (this.value || '')) {
                this.editor.clipboard.dangerouslyPasteHTML(this.value || '');
            }
        }
     }"
     x-init="
        editor = new Quill($refs.editor, {
            theme: 'snow',
            placeholder: '{{ $placeholder ?? '내용을 입력하세요' }}',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link'],
                    ['clean']
                ]
            }
        });
        editor.clipboard.dangerouslyPasteHTML(value || '');
        editor.on('text-change', () => syncFromEditor());
        $refs.editor.closest('.korean-bbs-quill')?.querySelectorAll('.ql-toolbar button').forEach(button => button.type = 'button');
        $watch('value', () => {
            if (!sourceMode) {
                syncToEditor();
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
                            syncToEditor();
                        }
                    "></button>
        </div>
    @endif

    <div x-show="!sourceMode" class="korean-bbs-quill @error('content') border border-red-400 rounded-lg @enderror">
        <div x-ref="editor"></div>
    </div>

    @if($allowSourceView)
        <textarea x-show="sourceMode"
                  x-model="value"
                  rows="{{ $rows ?? 12 }}"
                  spellcheck="false"
                  class="w-full min-h-72 border border-gray-300 rounded-lg px-3 py-2.5 font-mono text-xs resize-y focus:outline-none focus:ring-1 focus:ring-blue-400 @error('content') border-red-400 @enderror"
                  placeholder="<p>HTML 소스를 입력하세요</p>"></textarea>
    @endif
</div>
