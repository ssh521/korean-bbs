<textarea wire:model="content"
          rows="{{ $rows ?? 12 }}"
          placeholder="{{ $placeholder ?? '내용을 입력하세요' }}"
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm resize-y focus:outline-none focus:ring-1 focus:ring-blue-400 @error('content') border-red-400 @enderror"></textarea>
