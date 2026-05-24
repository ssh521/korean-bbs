@once
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
@endonce

@php($editorId = 'korean-bbs-tinymce-' . uniqid())
@php($allowSourceView = config('korean-bbs.editors.allow_source_view', true))

<div wire:ignore
     x-data="{
        value: @entangle('content').live,
        sourceMode: false,
        editor: null,
        syncToEditor() {
            if (this.editor && this.editor.getContent() !== (this.value || '')) {
                this.editor.setContent(this.value || '');
            }
        }
     }"
     x-init="
        tinymce.init({
            target: $refs.editor,
            base_url: 'https://cdn.jsdelivr.net/npm/tinymce@7',
            suffix: '.min',
            license_key: 'gpl',
            height: 320,
            menubar: false,
            promotion: false,
            branding: false,
            plugins: 'autolink lists link code table wordcount',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | bullist numlist blockquote | link table code | removeformat',
            content_style: 'body { font-family: inherit; font-size: 14px; line-height: 1.625; } blockquote { margin: 1rem 0; padding: .75rem 1rem; border-left: 4px solid #d1d5db; background: #f9fafb; color: #4b5563; }',
            setup(instance) {
                editor = instance;
                instance.on('init', () => instance.setContent(value || ''));
                instance.on('change keyup undo redo input', () => value = instance.getContent());
            }
        });
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

    <div x-show="!sourceMode" class="@error('content') border border-red-400 rounded-lg @enderror">
        <textarea id="{{ $editorId }}" x-ref="editor"></textarea>
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
