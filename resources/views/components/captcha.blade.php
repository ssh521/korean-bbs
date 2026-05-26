@if($this->captchaEnabled)
    @if($this->captchaProvider === 'turnstile' && config('korean-bbs.captcha.turnstile.site_key'))
        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-600 mb-1">
                자동등록 방지 <span class="text-red-500">*</span>
            </label>
            <div wire:ignore>
                <div class="cf-turnstile"
                     data-sitekey="{{ config('korean-bbs.captcha.turnstile.site_key') }}"
                     data-callback="koreanBbsTurnstileSuccess"
                     data-expired-callback="koreanBbsTurnstileExpired"
                     data-error-callback="koreanBbsTurnstileExpired"></div>
            </div>
            @error('captchaToken') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        @once
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            <script>
                window.koreanBbsTurnstileSuccess = function (token) {
                    @this.set('captchaToken', token);
                };

                window.koreanBbsTurnstileExpired = function () {
                    @this.set('captchaToken', '');
                };

                window.addEventListener('korean-bbs-turnstile-reset', function () {
                    if (window.turnstile) {
                        window.turnstile.reset();
                    }
                });
            </script>
        @endonce
    @elseif($this->captchaProvider === 'recaptcha' && config('korean-bbs.captcha.recaptcha.site_key'))
        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-600 mb-1">
                자동등록 방지 <span class="text-red-500">*</span>
            </label>
            <div wire:ignore>
                <div class="g-recaptcha"
                     data-sitekey="{{ config('korean-bbs.captcha.recaptcha.site_key') }}"
                     data-callback="koreanBbsRecaptchaSuccess"
                     data-expired-callback="koreanBbsRecaptchaExpired"
                     data-error-callback="koreanBbsRecaptchaExpired"></div>
            </div>
            @error('captchaToken') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        @once
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <script>
                window.koreanBbsRecaptchaSuccess = function (token) {
                    @this.set('captchaToken', token);
                };

                window.koreanBbsRecaptchaExpired = function () {
                    @this.set('captchaToken', '');
                };

                window.addEventListener('korean-bbs-recaptcha-reset', function () {
                    if (window.grecaptcha) {
                        window.grecaptcha.reset();
                    }
                });
            </script>
        @endonce
    @else
        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-600 mb-1">
                자동등록 방지 <span class="text-red-500">*</span>
            </label>
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="flex items-center justify-center min-w-28 px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700">
                    {{ $this->captchaQuestion }} =
                </div>
                <input type="text" wire:model="captchaAnswer"
                       inputmode="numeric"
                       autocomplete="off"
                       placeholder="답 입력"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 @error('captchaAnswer') border-red-400 @enderror">
                <button type="button"
                        wire:click="refreshCaptcha"
                        class="shrink-0 text-sm text-gray-500 hover:text-gray-700 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    새로고침
                </button>
            </div>
            @error('captchaAnswer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    @endif
@endif
