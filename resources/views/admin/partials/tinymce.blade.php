@php
    /**
     * Reusable TinyMCE rich-text editor (no build step, loaded from CDN).
     *
     * @var string $name        Textarea/input name
     * @var string $value       Initial HTML
     * @var string $uploadImageUrl
     * @var string $uploadFileUrl
     */
    $editorId = 'tinymce_' . preg_replace('/[^a-zA-Z0-9]/', '_', $name);
@endphp

<textarea id="{{ $editorId }}" name="{{ $name }}" class="input">{{ $value ?? '' }}</textarea>

@once
    @push('scripts')
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    @endpush
@endonce

@push('scripts')
<script>
    (function () {
        function initEditor() {
            if (!window.tinymce) { window.setTimeout(initEditor, 120); return; }

            window.tinymce.init({
                selector: '#{{ $editorId }}',
                menubar: false,
                branding: false,
                height: 520,
                plugins: 'lists link image code table media',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media table | code',
                content_style: 'body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; font-size: 15px; }',
                automatic_uploads: true,
                images_upload_url: @js($uploadImageUrl),
                file_picker_types: 'file image',
                images_upload_credentials: true,
                images_reuse_filename: false,
                file_picker_callback: function (callback, value, meta) {
                    var input = document.createElement('input');
                    input.type = 'file';
                    input.accept = meta.filetype === 'image' ? 'image/*' : '*/*';
                    input.onchange = function () {
                        var file = input.files && input.files[0];
                        if (!file) return;
                        var formData = new FormData();
                        formData.append('file', file);
                        fetch(@js($uploadFileUrl), {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: formData,
                            credentials: 'same-origin'
                        })
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (data && data.location) { callback(data.location, { text: file.name }); return; }
                            throw new Error('Upload falhou');
                        })
                        .catch(function (e) { console.error(e); alert('Falha no upload do arquivo.'); });
                    };
                    input.click();
                },
                setup: function (editor) {
                    editor.on('change keyup SetContent', function () {
                        editor.save();
                    });
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initEditor);
        } else {
            initEditor();
        }
    })();
</script>
@endpush
