
  jQuery( function($){
    // on upload button click
    $( 'body' ).on( 'click', '.rudr-upload', function( event ){
      event.preventDefault(); // prevent default link click and page refresh
      
      const button = $(this)
      const imageId = button.next().next().val();
      
      const customUploader = wp.media({
        title: 'Insert image', // modal window title
        library : {
          // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
          type : 'image'
        },
        button: {
          text: 'Use this image' // button label text
        },
        multiple: false
      }).on( 'select', function() { // it also has "open" and "close" events
        const attachment = customUploader.state().get( 'selection' ).first().toJSON();
        button.removeClass( 'button' ).html( '<img style="max-width: 450px;" src="' + attachment.url + '">'); // add image instead of "Upload Image"
        button.next().show(); // show "Remove image" link
        button.next().next().val( attachment.id ); // Populate the hidden field with image ID
      })
      
      // already selected images
      customUploader.on( 'open', function() {

        if( imageId ) {
          const selection = customUploader.state().get( 'selection' )
          attachment = wp.media.attachment( imageId );
          attachment.fetch();
          selection.add( attachment ? [attachment] : [] );
        }
        
      })

      customUploader.open()
    
    });
    // on remove button click
    $( 'body' ).on( 'click', '.rudr-remove', function( event ){
      event.preventDefault();
      const button = $(this);
      $('input[name="rudr_img"]').val( '' ); // emptying the hidden field
      $('.rudr-upload').addClass( 'button' ).html( 'Upload image' ); // replace the image with text
      $(this).remove();
    });
  });