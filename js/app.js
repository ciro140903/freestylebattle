//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var input; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

var audio = new Audio("beat/base.mp3");
audio.loop = true;

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);

function startRecording() {
	console.log("recordButton clicked");

	audio.play();

	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/

    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia()
	*/

	recordButton.disabled = true;
	stopButton.disabled = false;
	pauseButton.disabled = false

	recordButton.innerHTML = "Recording...";

	/*
    	We're using the standard promise based getUserMedia()
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();

		//update the format
		console.log("Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz");

		/*  assign to gumStream for later use  */
		gumStream = stream;

		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);

		/*
			Create the Recorder object and configure to record mono sound (1 channel)
			Recording 2 channels  will double the file size
		*/
		rec = new Recorder(input,{numChannels:1})

		//start the recording process
		rec.record()

		console.log("Recording started");

	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
    	pauseButton.disabled = true
	});
}

function pauseRecording(){
	console.log("pauseButton clicked rec.recording=",rec.recording );
	if (rec.recording){
		//pause
		rec.stop();
		pauseButton.innerHTML="Resume";
		recordButton.innerHTML = "Record";
		audio.pause();
	}else{
		//resume
		rec.record()
		pauseButton.innerHTML="Pause";
		recordButton.innerHTML = "Recording...";
		audio.play();

	}
}

function stopRecording() {
	console.log("stopButton clicked");

	//disable the stop button, enable the record too allow for new recordings
	stopButton.disabled = true;
	recordButton.disabled = true;
	pauseButton.disabled = true;

	document.getElementById('controls').style.visibility = 'hidden';
	document.getElementById('controls').style.hidden = true;

	recordButton.innerHTML = "Record";

	//reset button just in case the recording is stopped while paused
	pauseButton.innerHTML="Pause";

	//tell the recorder to stop the recording
	rec.stop();
	audio.pause();

	//stop microphone access
	gumStream.getAudioTracks()[0].stop();

	//create the wav blob and pass it on to createDownloadLink
	rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {

	var url = URL.createObjectURL(blob);
	var au = document.createElement('audio');
	var li = document.createElement('li');
	var link = document.createElement('a');

	//name of .wav file to use during upload and download (without extendion)
	// var filename = Math.floor(Math.random() * 99999999999999);

	var filename = document.getElementsByName('battle_id')[0].content;


//	var filename = document.currentScript.getAttribute('battle_id');

	//add controls to the <audio> element
	au.controls = true;
	au.src = url;

	//save to disk link
	// link.href = url;
	// link.download = filename+".wav"; //download forces the browser to donwload the file using the  filename
	// link.innerHTML = "Save to disk";

	//add the new audio element to li
	li.appendChild(au);

	// //add the filename to the li
	// li.appendChild(document.createTextNode(filename+".wav "))
	//
	// //add the save to disk link to li
	// li.appendChild(link);

	//upload link
	var upload = document.createElement('a');
	upload.style.fontFamily = "Verdana";
	upload.style.fontWeight = "bold";
	upload.style.padding = "10px";
	upload.style.border = "2px solid darkgreen";
	upload.style.borderRadius = "10px";
	upload.style.textDecoration = "none";
	upload.style.backgroundColor = "green";
	upload.style.marginLeft = "50px";
	upload.href="#";
	upload.innerHTML = "SALVA";
	upload.addEventListener("click", function(event){

			upload.style.display = 'none';
			upload.style.visibility = 'hidden';

			// var confirm = document.createElement('p');
			// confirm.innerHTML = "UPLOADED!";

		  var xhr=new XMLHttpRequest();
		  xhr.onload=function(e) {
		      if(this.readyState === 4) {
		          console.log("Server returned: ",e.target.responseText);
							stopButton.disabled = true;
							recordButton.disabled = true;
							pauseButton.disabled = true;
		      }
		  };
		  var fd=new FormData();
		  fd.append("audio_data",blob, filename);
		  xhr.open("POST","save_record.php?battle_id=" + filename,true);
		  xhr.send(fd);

			//location = "record.php?battle_id=" + filename;

	})
	li.appendChild(document.createTextNode (" "))//add a space in between
	li.appendChild(upload)//add the upload link to li

	//add the li element to the ol
	recordingsList.appendChild(li);

}
