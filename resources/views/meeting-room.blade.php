<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting: {{ $meeting->title }} - Bitsa Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-primary: #000000;
            --text-primary: #ffffff;
            --accent-cyan: #00fff5;
            --accent-purple: #bf00ff;
            --accent-pink: #ff006e;
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Space Grotesk', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .meeting-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .meeting-header {
            background: rgba(15, 15, 25, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 1rem 2rem;
            z-index: 1000;
        }

        .meeting-content {
            flex: 1;
            display: flex;
            gap: 1rem;
            padding: 1rem;
            overflow: hidden;
            background: rgba(10, 10, 20, 0.8);
        }

        .video-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            overflow-y: auto;
            padding: 1rem;
        }

        .video-participant {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            aspect-ratio: 16/9;
            position: relative;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .video-participant.host {
            border-color: var(--accent-pink);
        }

        .video-participant video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 13px;
        }

        .participant-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            padding: 1rem;
            border-radius: 0 0 13px 13px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .participant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-purple), var(--accent-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #000;
            flex-shrink: 0;
        }

        .participant-name {
            font-weight: 600;
            color: white;
        }

        .participant-status {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
        }

        .meeting-controls {
            background: rgba(15, 15, 25, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 1rem 2rem;
            z-index: 1000;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .control-btn.leave {
            background: #dc3545;
            color: white;
            width: 60px;
            height: 60px;
        }

        .control-btn.leave:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        .control-btn.muted {
            background: #6c757d !important;
        }

        /* Join meeting modal */
        .join-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .join-content {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .join-icon {
            font-size: 4rem;
            color: var(--accent-cyan);
            margin-bottom: 1rem;
        }

        .btn-join {
            background: var(--accent-cyan);
            color: #000;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            margin: 0.5rem;
            cursor: pointer;
        }

        .btn-join-audio {
            background: var(--accent-purple);
            color: #fff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            margin: 0.5rem;
            cursor: pointer;
        }

        .btn-join-without {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 0.75rem 2rem;
            border-radius: 25px;
            margin: 0.5rem;
            cursor: pointer;
        }

        .loading-state {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100%;
            color: rgba(255,255,255,0.7);
        }

        .error-state {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100%;
            color: #dc3545;
            text-align: center;
            padding: 2rem;
        }

        .error-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .permission-prompt {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-cyan);
        }
    </style>
</head>
<body>
    <!-- Join Meeting Modal - Shows first -->
    <div class="join-modal" id="joinModal">
        <div class="join-content">
            <div class="join-icon">
                <i class="bi bi-camera-video"></i>
            </div>
            <h3>Join Meeting</h3>
            <p>You're about to join: <strong>{{ $meeting->title }}</strong></p>
            
            <div class="permission-prompt">
                <p><small>Choose how you want to join:</small></p>
            </div>
            
            <div>
                <button class="btn-join" onclick="joinWithVideoAudio()">
                    <i class="bi bi-camera-video"></i> Join with Video & Audio
                </button>
                <button class="btn-join-audio" onclick="joinWithAudioOnly()">
                    <i class="bi bi-mic"></i> Join with Audio Only
                </button>
                <button class="btn-join-without" onclick="joinWithoutMedia()">
                    <i class="bi bi-headset"></i> Join Without Media
                </button>
            </div>
            
            <div class="mt-3">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    Your browser will ask for permission to use camera/microphone
                </small>
            </div>
        </div>
    </div>

    <!-- Meeting Container - Hidden until joined -->
    <div class="meeting-container" id="meetingContainer" style="display: none;">
        <div class="meeting-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">{{ $meeting->title }}</h4>
                    <small class="text-muted">Meeting ID: {{ $meeting->meeting_id }}</small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-danger">LIVE</span>
                    <span class="text-muted">
                        <i class="bi bi-people"></i> 
                        <span id="participantCount">{{ $meeting->participants_count }}</span> participants
                    </span>
                    <span class="text-muted" id="connectionStatus">
                        <i class="bi bi-wifi"></i> Connected
                    </span>
                </div>
            </div>
        </div>

        <div class="meeting-content">
            <div class="video-grid" id="videoGrid">
                <!-- Local participant will be added here -->
            </div>
        </div>

        <div class="meeting-controls">
            <div class="d-flex justify-content-center align-items-center gap-3">
                <button class="control-btn" style="background: var(--accent-cyan); color: #000;" id="toggleVideo" disabled>
                    <i class="bi bi-camera-video"></i>
                </button>
                <button class="control-btn" style="background: var(--accent-purple); color: #fff;" id="toggleAudio" disabled>
                    <i class="bi bi-mic"></i>
                </button>
                <button class="control-btn leave" id="leaveMeeting">
                    <i class="bi bi-telephone-x"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        class SecureMeetingRoom {
            constructor(meetingId) {
                this.meetingId = meetingId;
                this.localStream = null;
                this.isVideoEnabled = false;
                this.isAudioEnabled = false;
                this.hasMediaAccess = false;
                
                this.initializeMeeting();
            }

            initializeMeeting() {
                console.log('Initializing secure meeting room...');
                this.setupEventListeners();
                // Show join modal immediately - don't request permissions automatically
                this.showJoinModal();
            }

            showJoinModal() {
                document.getElementById('joinModal').style.display = 'flex';
                document.getElementById('meetingContainer').style.display = 'none';
            }

            hideJoinModal() {
                document.getElementById('joinModal').style.display = 'none';
                document.getElementById('meetingContainer').style.display = 'flex';
            }

            // Join with video and audio
            async joinWithVideoAudio() {
                await this.requestMediaPermissions({ video: true, audio: true });
            }

            // Join with audio only
            async joinWithAudioOnly() {
                await this.requestMediaPermissions({ video: false, audio: true });
            }

            // Join without any media
            async joinWithoutMedia() {
                console.log('Joining meeting without media');
                this.hideJoinModal();
                this.showLocalParticipant(false);
                this.updateConnectionStatus('Connected (Audio/Video disabled)');
                this.initializeMeetingRoom();
            }

            async requestMediaPermissions(constraints) {
                try {
                    console.log('Requesting media permissions with constraints:', constraints);
                    
                    // Add timeout to prevent hanging
                    const timeoutPromise = new Promise((_, reject) => {
                        setTimeout(() => reject(new Error('Media request timeout - please check your camera/microphone permissions')), 10000);
                    });

                    const mediaPromise = navigator.mediaDevices.getUserMedia(constraints);
                    
                    this.localStream = await Promise.race([mediaPromise, timeoutPromise]);
                    
                    console.log('Media access granted successfully');
                    this.hasMediaAccess = true;
                    this.hideJoinModal();
                    this.showLocalParticipant(true);
                    this.enableMediaControls();
                    this.initializeMeetingRoom();
                    
                } catch (error) {
                    console.error('Error accessing media devices:', error);
                    this.handleMediaError(error);
                }
            }

            handleMediaError(error) {
                let errorMessage = 'Could not access media devices. ';
                
                if (error.name === 'NotAllowedError') {
                    errorMessage = 'Permission denied. Please allow camera/microphone access in your browser settings and try again.';
                } else if (error.name === 'NotFoundError') {
                    errorMessage = 'No camera or microphone found. Please check your device connections.';
                } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                    errorMessage = 'Camera/microphone is in use by another application. Please close other apps and try again.';
                } else if (error.message === 'Media request timeout') {
                    errorMessage = 'Request timed out. Your device might be busy or permissions are blocked.';
                } else if (error.name === 'OverconstrainedError') {
                    errorMessage = 'Cannot find camera with required specifications. Try different settings.';
                } else {
                    errorMessage += error.message;
                }

                this.showErrorState(errorMessage);
            }

            showErrorState(message) {
                const joinContent = document.querySelector('.join-content');
                joinContent.innerHTML = `
                    <div class="error-state">
                        <i class="bi bi-exclamation-triangle"></i>
                        <h4>Media Access Issue</h4>
                        <p>${message}</p>
                        <div class="mt-3">
                            <button class="btn-join" onclick="window.meetingRoom.retryWithVideoAudio()">
                                <i class="bi bi-arrow-repeat"></i> Try Again
                            </button>
                            <button class="btn-join-without" onclick="window.meetingRoom.joinWithoutMedia()">
                                Join Without Media
                            </button>
                        </div>
                    </div>
                `;
            }

            retryWithVideoAudio() {
                // Reload the join modal
                location.reload();
            }

            showLocalParticipant(hasMedia) {
                const videoGrid = document.getElementById('videoGrid');
                
                const participantHTML = `
                    <div class="video-participant host" id="localParticipant">
                        ${hasMedia ? `
                            <video id="localVideo" autoplay muted playsinline></video>
                        ` : ''}
                        <div class="participant-info">
                            <div class="participant-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="participant-name">{{ Auth::user()->name }} (You)</div>
                                <div class="participant-status">
                                    Host • ${hasMedia ? 'Live' : 'Audio/Video disabled'}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                videoGrid.innerHTML = participantHTML;
                
                if (hasMedia && this.localStream) {
                    const localVideo = document.getElementById('localVideo');
                    localVideo.srcObject = this.localStream;
                }
            }

            enableMediaControls() {
                const videoBtn = document.getElementById('toggleVideo');
                const audioBtn = document.getElementById('toggleAudio');
                
                if (this.localStream) {
                    if (this.localStream.getVideoTracks().length > 0) {
                        videoBtn.disabled = false;
                        this.isVideoEnabled = true;
                    }
                    if (this.localStream.getAudioTracks().length > 0) {
                        audioBtn.disabled = false;
                        this.isAudioEnabled = true;
                    }
                }
            }

            setupEventListeners() {
                document.getElementById('toggleVideo').addEventListener('click', () => {
                    this.toggleVideo();
                });

                document.getElementById('toggleAudio').addEventListener('click', () => {
                    this.toggleAudio();
                });

                document.getElementById('leaveMeeting').addEventListener('click', () => {
                    this.leaveMeeting();
                });
            }

            toggleVideo() {
                if (this.localStream && this.localStream.getVideoTracks().length > 0) {
                    const videoTrack = this.localStream.getVideoTracks()[0];
                    this.isVideoEnabled = !videoTrack.enabled;
                    videoTrack.enabled = this.isVideoEnabled;
                    
                    const btn = document.getElementById('toggleVideo');
                    if (this.isVideoEnabled) {
                        btn.innerHTML = '<i class="bi bi-camera-video"></i>';
                        btn.style.background = 'var(--accent-cyan)';
                    } else {
                        btn.innerHTML = '<i class="bi bi-camera-video-off"></i>';
                        btn.style.background = '#6c757d';
                    }
                }
            }

            toggleAudio() {
                if (this.localStream && this.localStream.getAudioTracks().length > 0) {
                    const audioTrack = this.localStream.getAudioTracks()[0];
                    this.isAudioEnabled = !audioTrack.enabled;
                    audioTrack.enabled = this.isAudioEnabled;
                    
                    const btn = document.getElementById('toggleAudio');
                    if (this.isAudioEnabled) {
                        btn.innerHTML = '<i class="bi bi-mic"></i>';
                        btn.style.background = 'var(--accent-purple)';
                    } else {
                        btn.innerHTML = '<i class="bi bi-mic-mute"></i>';
                        btn.style.background = '#6c757d';
                    }
                }
            }

            initializeMeetingRoom() {
                // Here you would initialize your WebRTC connection
                // For now, we'll just show success
                this.updateConnectionStatus('Connected to meeting');
                console.log('Meeting room initialized successfully');
            }

            updateConnectionStatus(status) {
                const statusElement = document.getElementById('connectionStatus');
                if (statusElement) {
                    statusElement.innerHTML = `<i class="bi bi-wifi"></i> ${status}`;
                }
            }

            async leaveMeeting() {
                if (confirm('Are you sure you want to leave the meeting?')) {
                    await this.cleanup();
                    window.location.href = '{{ route("user.dashboard") }}';
                }
            }

            async cleanup() {
                // Stop all media tracks
                if (this.localStream) {
                    this.localStream.getTracks().forEach(track => track.stop());
                }
                
                // Notify server about leaving
                try {
                    await fetch(`/api/meetings/{{ $meeting->id }}/leave`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        credentials: 'same-origin',
                        keepalive: true
                    });
                } catch (error) {
                    console.error('Error leaving meeting:', error);
                }
            }
        }

        // Global functions for the join modal
        function joinWithVideoAudio() {
            if (window.meetingRoom) {
                window.meetingRoom.joinWithVideoAudio();
            }
        }

        function joinWithAudioOnly() {
            if (window.meetingRoom) {
                window.meetingRoom.joinWithAudioOnly();
            }
        }

        function joinWithoutMedia() {
            if (window.meetingRoom) {
                window.meetingRoom.joinWithoutMedia();
            }
        }

        // Initialize meeting room when page loads
        document.addEventListener('DOMContentLoaded', function() {
            window.meetingRoom = new SecureMeetingRoom({{ $meeting->id }});
        });

        // Handle browser back button
        window.addEventListener('beforeunload', function() {
            if (window.meetingRoom) {
                window.meetingRoom.cleanup();
            }
        });
    </script>
</body>
</html>