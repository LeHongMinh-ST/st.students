@props(['id', 'name', 'value' => ''])

<div
    wire:ignore
    x-data="{
        value: @entangle($attributes->wire('model')),
        init() {
            ClassicEditor
                .create(this.$refs.editor, {
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'link',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'outdent',
                            'indent',
                            '|',
                            'blockQuote',
                            'insertTable',
                            'undo',
                            'redo'
                        ]
                    },
                    language: 'vi',
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells'
                        ]
                    },
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        this.value = editor.getData();
                    });

                    editor.setData(this.value);

                    // Update editor when the value changes externally
                    this.$watch('value', (value) => {
                        if (editor.getData() !== value) {
                            editor.setData(value);
                        }
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }"
>
    <div wire:ignore>
        <textarea
            x-ref="editor"
            id="{{ $id }}"
            name="{{ $name }}"
            class="form-control"
        >{{ $value }}</textarea>
    </div>
</div>
