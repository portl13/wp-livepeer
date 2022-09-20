<style>
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
</style>
<?php if( $options ): ?>

  <div style="text-align: center">
    <video id="lpwp-video"></video>
    <button id="lpwp-button">Start</button>
  </div>

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

      button.onclick = () => {
        
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

        const streamKey = '<?php echo $stream_created['streamKey']; ?>';

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

<?php else: ?>

  <p>Please update your Livepeer options in the admin area.</p>
<?php endif;?>