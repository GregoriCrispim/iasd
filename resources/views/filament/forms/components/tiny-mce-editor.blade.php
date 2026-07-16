@php
    /** @var \App\Filament\Forms\Components\TinyMceEditor $component */
    $uploadImageUrl = $getUploadImageUrl();
    $uploadFileUrl = $getUploadFileUrl();
@endphp

<div
    x-data="{
        initialValue: @js($getState()),
        editor: null,
        init() {
            const textarea = this.$refs.textarea;

            const ensureTinyMce = () => new Promise((resolve, reject) => {
                if (window.tinymce) return resolve();
                const script = document.createElement('script');
                script.src = 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js';
                script.referrerPolicy = 'origin';
                script.onload = resolve;
                script.onerror = () => reject(new Error('Falha ao carregar o TinyMCE'));
                document.head.appendChild(script);
            });

            ensureTinyMce().then(() => {
                const id = textarea.id;

                // Cleanup old instance (Livewire re-renders)
                if (window.tinymce.get(id)) {
                    window.tinymce.get(id).remove();
                }

                window.tinymce.init({
                    target: textarea,
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
                    file_picker_callback: (callback, value, meta) => {
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.accept = meta.filetype === 'image' ? 'image/*' : '*/*';
                        input.onchange = () => {
                            const file = input.files?.[0];
                            if (!file) return;

                            const formData = new FormData();
                            formData.append('file', file);

                            fetch(@js($uploadFileUrl ?? $uploadImageUrl), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                                },
                                body: formData,
                                credentials: 'same-origin',
                            })
                                .then((r) => r.json())
                                .then((data) => {
                                    if (data?.location) {
                                        callback(data.location, { text: file.name });
                                        return;
                                    }
                                    throw new Error('Upload falhou');
                                })
                                .catch((e) => {
                                    console.error(e);
                                });
                        };
                        input.click();
                    },
                    setup: (editor) => {
                        this.editor = editor;
                        editor.on('init', () => {
                            editor.setContent(this.initialValue || '');
                        });
                        editor.on('change keyup SetContent', () => {
                            $wire.set(@js($getStatePath()), editor.getContent());
                        });
                    },
                });
            });
        },
    }"
    x-init="init()"
    wire:ignore
>
    <textarea
        id="{{ $getId() }}"
        x-ref="textarea"
        class="hidden"
    ></textarea>
</div>

