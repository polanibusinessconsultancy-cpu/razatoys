@php
  $footer = $footer ?? \App\Models\FooterSetting::getSettings();
  $whatsappPhone = preg_replace('/\D+/', '', $footer->phone ?? '923249206345');
@endphp

@if(!empty($whatsappPhone))
  <a
    href="https://wa.me/{{ $whatsappPhone }}?text={{ rawurlencode('Assalam o Alaikum, I want to contact RazaToys support.') }}"
    class="raza-whatsapp-fab"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Chat with RazaToys on WhatsApp"
    title="Chat with RazaToys on WhatsApp"
  >
    <svg viewBox="0 0 24 24" fill="currentColor" width="30" height="30" aria-hidden="true">
      <path d="M12.01 2.002c-5.513 0-9.99 4.478-9.99 9.991 0 1.763.459 3.484 1.332 5.002l-1.417 5.176 5.309-1.392a9.96 9.96 0 0 0 4.766 1.214h.004c5.513 0 9.99-4.478 9.99-9.992 0-5.513-4.477-9.99-9.994-9.99z"/>
    </svg>
  </a>
@endif
