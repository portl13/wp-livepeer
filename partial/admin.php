<style>
  input[disabled] {
    color: #202020;
    border: none;
    box-shadow: none;
  }
  input[type="text"] {
    max-width: 100%;
    width: 100%;
  }
  #lpwp-video {
    height: 300px;
    width: 400px;
    border: 1px solid rgb(158, 140, 252);
    background-color: black;
    border-radius: 3px;
  }

  #lpwp-button {
    font-size: 18px;
    border-radius: 3px;
    border: 1px solid rgb(158, 140, 252);
    color: #202020;
    background: none;
    cursor: pointer;
    padding: 8px 16px;
    margin: 0 auto;
    margin-top: 10px;
  }

  h2 + .row > .card {margin-top: 0;}
  .row .card + .card {
    flex-grow: 2;
    flex-basis: 66.666%;
  }
  .row .card {
    flex-grow: 1;
    flex-basis: 33.333%;
    max-width: 100%;
    min-width: 450px;
  }
  .row {
    display: flex;
    flex-direction: row;
    gap: 10px 10px;
  }
  #create_event_button {
    padding-right: 34px;
    box-sizing: content-box;
    position: relative;
    display: inline-block;
  }
  .link-out:after {
    content: '';
    background-image: url(<?php echo plugin_dir_url(__DIR__).'/assets/img/link-out.png';?>);
    background-size: 18px 18px;
    opacity: .6;
    width: 18px;
    height: 18px;
    display: block;
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
  }
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;

  }

  .switch input { 
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>

<div class="wrap" style="width:90%">
    
  <form method="POST" action="<?=get_admin_url();?>admin.php?page=livepeer-options">


   
  <table class="form-table">
    <tbody>
      <tr>
        <td style="text-align: right;">
          <input type="submit" name="publish" value="Save Options" class="button" />
        </td>
      </tr>
    </tbody>
  </table>
  <hr>

  <?php if( $options ): ?>

    <h2>LIVE Stream Now</h2>
    
    <div class="row">

      <!-- VIDEO CARD -->
      <div class="card">

        <?php if( $global_stream_config ): ?>

          <div>
            <video style="width: 100%;" id="lpwp-video"></video>
          </div>

          <p><label for=""><strong>Use this shortcode on any Page to show the Stream</strong></label><br />
            <span style="margin-top: 3px; display: inline-block;padding: 3px 6px; background-color: #e1e1e1; font-size: 18px;"><strong>[livepeer_player]</strong></span></p>
            
          <hr style="margin-top: 20px;">
          <div>
            <button style="width: 100%;" id="lpwp-button">Start Live Streaming</button>
          </div>

        <?php else: ?>

          <p style="font-size: 1.3em;"><strong style="font-size: 1.6em;">Next Step</strong><br /> Give your LIVE stream a name <span style="font-size: 1.4em;position: relative; top: 2px;">&rarr;</span></p>
        <?php endif;?>

        <?php if( $global_stream_config ) : ?>
          <h2>Livepeer Stream Information</h2>
          <table>
            <tbody>
              
              <tr>
                <th>
                  <label for="input-text">Stream Id</label>
                </th>
                <td>
                  <?php echo $global_stream_config ? $global_stream_config->id : 'Create a Stream to get this info';?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="input-text">Stream Key</label>
                </th>
                <td>
                  <?php echo $global_stream_config ? $global_stream_config->streamKey : 'Create a Stream to get this info';?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="input-text">SRT Ingest</label>
                </th>
                <td>
                  <?php echo $global_stream_config ? 'srt://rtmp.livepeer.com:2935?streamid='.$global_stream_config->streamKey : 'Create a Stream to get this info';?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="input-text">RMTP Ingest</label>
                </th>
                <td>
                  rtmp://rtmp.livepeer.com/live
                </td>
              </tr>
            </tbody>
          </table>
        <?php endif;?>

      </div>

      <!-- Stream Details-->
      <div class="card">    

        <table class="form-table" style="max-width: 100%; width: 100%">
          <tbody>
            <tr>
              <th>
                <label for="input-text">Livepeer Stream Name</label>
              </th>
              <td>
                <input type="text" name="livepeer_stream_name" placeholder="..."  value="<?php echo isset($options['livepeer_stream_name']) ? $options['livepeer_stream_name'] : '';?>"><br />
              </td>
            </tr>
            <?php if( $global_stream_config ):?>
              <tr>
                <th>Channel Offline Banner</th>
                <td>
                    
                    <?php if( !isset($options['rudr_img']) ) : ?>



                      <a href="#" class="button rudr-upload">Upload image</a>
                      <p><a href="#" class="rudr-remove" style="display:none">Remove image</a></p>
                      <input type="hidden" name="rudr_img" value="">

                    <?php else: 
                        $image = wp_get_attachment_image_url( $options['rudr_img'], 'medium' ) ?>
                      <a href="#" class="rudr-upload">
                        <img src="<?php echo esc_url( $image ) ?>" />
                      </a><br />
                      <p><a href="#" class="rudr-remove">Remove image</a></p>
                      <input type="hidden" name="rudr_img" value="<?php echo absint( $options['rudr_img'] ) ?>">
                    <?php endif; ?>
                    


                </td>
              </tr>

              <tr>
                <th>Stream Recording</th>
                <td>
                  <label class="switch">
                    <input type="checkbox" name="livepeer_stream_recording" <?php echo isset($options['livepeer_stream_recording']) && $options['livepeer_stream_recording'] ? 'checked' : '';?>>
                    <span class="slider round"></span>
                  </label>
                </td>
              </tr>

              <tr>
                <th>
                  <label for="input-text">Playback URL</label>
                </th>
                <td>
                  <a target="_blank" href="<?php echo site_url() .'/channel/'.sanitize_title($options['livepeer_stream_name']);?>/"><?php echo site_url() .'/channel/'.sanitize_title($options['livepeer_stream_name']);?>/</a>
                </td>
              </tr>

              <tr>
                <th>Livepeer Events</th>
                <td>
                  <p><strong>Create an Event Page</strong></p>
                  <p>An Event Page is like a digital flyer to pass around to your friends on your socials! Add a Cover Image, Date & Time, and name the Event as well as a link to the channel page for your viewers to watch your LIVE stream.<br /><br /></p>
                  <p><a id="create_event_button" target="_blank" href="<?php echo site_url();?>/wp-admin/post-new.php?post_type=livepeer-events" class="btn button link-out">Create a Live Stream Event</a></p>
                </td>
              </tr>
            <?php endif; ?>

          </tbody>
        </table>

      </div>

    </div>

  <?php endif;?>

  <h2><?php esc_html_e( 'LivePeer Setup' ); ?></h2>
    
  <div class="card" style="max-width: 100%;width: 100%">
    <table class="form-table" style="max-width: 100%; width: 100%">
      <tbody>
        <tr>
          <th>
            <label for="input-text">LivePeer Api Key</label>
          </th>
          <td>
            <input type="text" name="LIVEPEER_API_TOKEN" placeholder="..." value="<?php echo isset($options['LIVEPEER_API_TOKEN']) ? $options['LIVEPEER_API_TOKEN'] : '';?>"><br />
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <hr style="margin-top: 30px;">

  <table class="form-table">
    <tbody>
      <tr>
        <td style="text-align: right;">
          <input type="submit" name="publish" value="Save Options" class="button" />
        </td>
      </tr>
    </tbody>
  </table>

  </form>

</div><!-- .wrap -->

<script type="text/javascript" src="https://unpkg.com/@livepeer/webrtmp-sdk@0.2.3/dist/index.js"></script>
<script type="text/javascript">
  
    const video = document.getElementById("lpwp-video");
    const button = document.getElementById("lpwp-button");

    video.volume = 0;
    const { Client } = webRTMP;

    let stream;
    async function setup() {
      stream = await navigator.mediaDevices.getUserMedia({
          video: true,
          audio: true
      });

      video.srcObject = stream;
      video.play();
    }


    setup();

    var live = 0;
    var session = false;

    button.onclick = (e) => {
      e.preventDefault();
      
      if( live == 1 ){
        console.log('going to stop');
        live = 0;
        session.close();
        button.text = 'Start';
        return;
      }
      console.log('going to start');

      live = 1;
      button.innerText = 'Stop';

      const streamKey = '<?php echo $global_stream_config->streamKey; ?>';

      if (!stream) {
        alert("Video stream not initialized yet.");
      }

      if (!streamKey) {
        alert("Invalid streamKey.");
        return;
      }

      const client = new Client();
      session = client.cast(stream, streamKey);

      session.on("open", () => {
        console.log("Stream started.");
        alert("Stream started; visit Livepeer Dashboard.");
      });

      session.on("close", () => {
        console.log('going to stop');
        button.innerText = 'Start';
        console.log("Stream stopped.");
      });

      session.on("error", (err) => {
        console.log(err);
        console.log("Stream error.", err.message);
      });
    };
</script>