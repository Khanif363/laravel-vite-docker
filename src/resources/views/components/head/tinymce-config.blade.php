 <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
 <script>
     tinymce.init({
         selector: 'textarea.editor', // Replace this CSS selector to match the placeholder element for TinyMCE
         height: 300,
         plugins: [
             'advlist',
             'autolink',
             'lists',
             'link',
             //  'image',
             'charmap',
             'preview',
             'anchor',
             'pagebreak',
             'searchreplace',
             'wordcount',
             'visualblocks',
             'visualchars',
             'code',
             'fullscreen',
             'insertdatetime',
             //  'media',
             'nonbreaking',
             'save',
             'table',
             'directionality',
             'emoticons',
             'template'
         ],
         toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
         toolbar2: 'print preview media | forecolor backcolor emoticons',
         image_advtab: true
     });
 </script>
