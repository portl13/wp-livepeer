

function checkStreamHealth(){
  var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "https://livepeer.studio/data/stream/"+livepeer_jsobject.stream_id+"/health", true);
    xhttp.setRequestHeader('Authorization', 'Bearer '+livepeer_jsobject.api_token);

  xhttp.onreadystatechange = function() {

    if( this.status == 200 && this.readyState == 4){

      let response = JSON.parse(this.response);
      
      if( response.conditions[0].status ){
        document.getElementById('cover-image').style.display = 'none';
        document.getElementById('video-container').style.display = 'block';
      } else {
        document.getElementById('cover-image').style.display = 'block';
        document.getElementById('video-container').style.display = 'none';
      }

    } else {
        document.getElementById('cover-image').style.display = 'block';
        document.getElementById('video-container').style.display = 'none';
    }
      
  };
  xhttp.send();
}
checkStreamHealth()

$timer = setInterval(function(){
  checkStreamHealth($timer);
},10000);

var player = videojs('hls-example',{
  autoplay: 'muted',
  aspectRatio: '16:9',
  fluid : true,
  notSupportedMessage : "This channel is currently offline.",
  suppressNotSupportedError: true
});

var video = document.getElementById('hls-example');
var onDurationChange = function(){
  console.log(video);
    if(video.readyState){
        
    }
};

      //player.play();