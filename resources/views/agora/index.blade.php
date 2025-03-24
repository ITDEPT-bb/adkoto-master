<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agora Web SDK Quickstart</title>
</head>

<body>
    <h2 class="left-align">Agora Web SDK Quickstart</h2>
    <div class="row">
        <div>
            <button type="button" id="join">Join</button>
            <button type="button" id="leave">Leave</button>
        </div>
    </div>
    <!-- Include the JavaScript file -->
    {{-- <script type="module" src="/src/main.js"></script>         --}}
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.23.1.js"></script>

    <script type="module">
        // import AgoraRTC from "agora-rtc-sdk-ng";

        // RTC client instance
        let client = null;
        // Local audio track
        let localAudioTrack = null;

        // Connection parameters
        let appId = "6e99ddb4c5e14ee79c0f4114936500dc";
        let channel = "testt";
        let token =
            "007eJxTYPgXMXddp3k/7/3qS//SboZ5v0+9+6vyyUPpjeH9kt6Hlh1VYDBLtbRMSUkySTZNNTRJTTW3TDZIMzE0NLE0NjM1MEhJZv/xIL0hkJFhRVMOIyMDBIL4rAwlqcUlJQwMAIVuI3o=";
        let uid = 0; // User ID

        // Initialize the AgoraRTC client
        function initializeClient() {
            client = AgoraRTC.createClient({
                mode: "rtc",
                codec: "vp8"
            });
            setupEventListeners();
        }

        // Handle client events
        function setupEventListeners() {
            // Set up event listeners for remote tracks
            client.on("user-published", async (user, mediaType) => {
                // Subscribe to the remote user when the SDK triggers the "user-published" event
                await client.subscribe(user, mediaType);
                console.log("subscribe success");
                // If the remote user publishes an audio track.
                if (mediaType === "audio") {
                    // Get the RemoteAudioTrack object in the AgoraRTCRemoteUser object.
                    const remoteAudioTrack = user.audioTrack;
                    // Play the remote audio track.
                    remoteAudioTrack.play();
                }
            });

            // Listen for the "user-unpublished" event
            client.on("user-unpublished", async (user) => {
                // Remote user unpublished
            });
        }

        // Create a local audio track
        async function createLocalAudioTrack() {
            localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
        }

        // Join the channel and publish local audio
        async function joinChannel() {
            await client.join(appId, channel, token, uid);
            await createLocalAudioTrack();
            await publishLocalAudio();
            console.log("Publish success!");
        }

        // Publish local audio track
        async function publishLocalAudio() {
            await client.publish([localAudioTrack]);
        }

        // Leave the channel and clean up
        async function leaveChannel() {
            localAudioTrack.close(); // Stop local audio
            await client.leave(); // Leave the channel
            console.log("Left the channel.");
        }

        // Set up button click handlers
        function setupButtonHandlers() {
            document.getElementById("join").onclick = joinChannel;
            document.getElementById("leave").onclick = leaveChannel;
        }

        // Start the basic call process
        function startBasicCall() {
            initializeClient();
            window.onload = setupButtonHandlers;
        }

        startBasicCall();
    </script>
</body>

</html>
