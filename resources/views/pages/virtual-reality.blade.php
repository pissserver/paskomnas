@extends('layouts.user_type.auth')

@section('title', 'Virtual Reality Paskomnas || PISS')

@section('content')

<div class="section position-relative transform-scale-0 d-flex justify-content-center align-items-center"
  style="height: 100vh; overflow: hidden;">
  <div class="container">
    <div class="row">
      <div class="col-lg-1 col-md-1 pt-5 pt-lg-0 ms-lg-5 text-center">
        <a href="javascript:;" class="avatar avatar-md border-0" data-bs-toggle="tooltip" data-bs-placement="left"
          title="{{ Auth::user()->name }}">
          <img class="border-radius-lg" alt="Image placeholder"
            src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('assets/img/default-profile.jpg') }}">
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-white border-radius-lg p-2 mt-2" data-bs-toggle="tooltip"
          data-bs-placement="left" title="Home">
          <i class="fas fa-home p-2"></i>
        </a>
        <button class="btn btn-white border-radius-lg p-2" type="button" data-bs-toggle="tooltip"
          data-bs-placement="left" title="Search">
          <i class="fas fa-search p-2"></i>
        </button>
        <button class="btn btn-white border-radius-lg p-2" type="button" data-bs-toggle="tooltip"
          data-bs-placement="left" title="Minimize">
          <i class="fas fa-ellipsis-h p-2"></i>
        </button>
      </div>
      <div class="col-lg-8 col-md-11">
        <div class="d-flex">
          {{-- <div class="me-auto">
            <h1 class="display-1 font-weight-bold mt-n4 mb-0">28°C</h1>
            <h6 class="text-uppercase mb-0 ms-1">Cloudy</h6>
          </div> --}}
          <div class="me-auto">
            <h1 id="current-time" class="display-1 font-weight-bold mt-n4 mb-0">--:--</h1>
            <h6 class="text-uppercase mb-0 ms-1">WIB</h6>
          </div>
          <div class="ms-auto">
            <img class="w-50 float-end mt-lg-n4" src="{{ asset('assets/img/small-logos/icon-sun-cloud.png') }}"
              alt="image sun">
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-4">
            <div class="card move-on-hover overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <h6 class="mb-0 me-3">08:00</h6>
                  <h6 class="mb-0">Synk up with Mark
                    <small class="text-secondary font-weight-normal">Hangouts</small>
                  </h6>
                </div>
                <hr class="horizontal dark">
                <div class="d-flex">
                  <h6 class="mb-0 me-3">09:30</h6>
                  <h6 class="mb-0">Gym <br />
                    <small class="text-secondary font-weight-normal">World Class</small>
                  </h6>
                </div>
                <hr class="horizontal dark">
                <div class="d-flex">
                  <h6 class="mb-0 me-3">11:00</h6>
                  <h6 class="mb-0">Design Review<br />
                    <small class="text-secondary font-weight-normal">Zoom</small>
                  </h6>
                </div>
              </div>
              <a href="javascript:;" class="bg-gray-100 w-100 text-center py-1" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Show More">
                <i class="fas fa-chevron-down text-primary"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 mt-4 mt-sm-0">
            <div class="card bg-gradient-dark move-on-hover">
              <div class="card-body">
                <div class="d-flex">
                  <h5 class="mb-0 text-white">To Do</h5>
                  <div class="ms-auto">
                    <h1 class="text-white text-end mb-0 mt-n2">7</h1>
                    <p class="text-sm mb-0 text-white">items</p>
                  </div>
                </div>
                <p class="text-white mb-0">Shopping</p>
                <p class="mb-0 text-white">Meeting</p>
              </div>
              <a href="javascript:;" class="w-100 text-center py-1" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Show More">
                <i class="fas fa-chevron-down text-white"></i>
              </a>
            </div>
            <div class="card move-on-hover mt-4">
              <div class="card-body">
                <div class="d-flex">
                  <p class="mb-0 mt-1"><i class="fa fa-envelope"></i></p>
                  <a href="{{ route('user.index') }}" class="ms-auto text-truncate" style="max-width: 130px;"
                    data-bs-toggle=" tooltip" data-bs-placement="top" title="Cek email anda">
                    {{ Auth::user()->email }}
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 mt-4 mt-sm-0">
            <div class="card card-background card-background-mask-primary move-on-hover align-items-start">
              <div class="cursor-pointer">
                <div class="full-background"
                  style="background-image: url('{{ asset('assets/img/curved-images/curved14.jpg') }}')">
                </div>
                <div class="card-body">
                  <h5 class="text-white mb-0" id="song-title">Some Kind Of Blues</h5>
                  <p class="text-white text-sm" id="song-artist">Deftones</p>

                  <audio id="audio-player" class="d-none">
                    <source id="audio-source" src="{{ asset('assets/music/some-kind-of-blues.mp3') }}" type="audio/mp3">
                    Your browser does not support the audio element.
                  </audio>

                  <div class="d-flex mt-5">
                    <button class="btn btn-outline-white rounded-circle p-2 mb-0" type="button" data-bs-toggle="tooltip"
                      data-bs-placement="top" title="Prev" onclick="previousTrack()">
                      <i class="fas fa-backward p-2"></i>
                    </button>
                    <button class="btn btn-outline-white rounded-circle p-2 mx-2 mb-0" type="button"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Play/Pause" onclick="togglePlay()">
                      <i class="fas fa-play p-2" id="play-icon"></i>
                    </button>
                    <button class="btn btn-outline-white rounded-circle p-2 mb-0" type="button" data-bs-toggle="tooltip"
                      data-bs-placement="top" title="Next" onclick="nextTrack()">
                      <i class="fas fa-forward p-2"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card move-on-hover mt-4">
              <div class="card-body">
                <div class="d-flex">
                  <p class="my-auto">Users</p>
                  <div class="ms-auto">
                    <div class="avatar-group">
                      <a href="javascript:;" class="avatar avatar-sm border-0 rounded-circle" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="2 New Messages">
                        <img alt="Image placeholder" src="{{ asset('assets/img/team-1.jpg') }}">
                      </a>
                      <a href="javascript:;" class="avatar avatar-sm border-0 rounded-circle" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="1 New Message">
                        <img alt="Image placeholder" src="{{ asset('assets/img/team-2.jpg') }}">
                      </a>
                      <a href="javascript:;" class="avatar avatar-sm border-0 rounded-circle" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="13 New Messages">
                        <img alt="Image placeholder" src="{{ asset('assets/img/team-3.jpg') }}">
                      </a>
                      <a href="javascript:;" class="avatar avatar-sm border-0 rounded-circle" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="7 New Messages">
                        <img alt="Image placeholder" src="{{ asset('assets/img/team-4.jpg') }}">
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Update waktu
    function updateTime() {
      const now = new Date();
      const hours = now.getHours().toString().padStart(2, '0');
      const minutes = now.getMinutes().toString().padStart(2, '0');
      const timeString = `${hours}:${minutes}`;
      document.getElementById('current-time').textContent = timeString;
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Daftar lagu
    const songs = [
      {
        title: "NIKI - Every Summertime",
        artist: "NIKI",
        file: "{{ asset('assets/music/NIKI - Every Summertime.mp3') }}"
      },
      {
        title: "NIKI - Backburner",
        artist: "NIKI",
        file: "{{ asset('assets/music/NIKI - Backburner.mp3') }}"
      },
      {
        title: "NIKI - You'll Be in My Heart",
        artist: "NIKI",
        file: "{{ asset('assets/music/NIKI - You will Be in My Heart.mp3') }}"
      }
    ];

    let currentSongIndex = 0;
    let hasUserPlayed = false;

    const audioPlayer = document.getElementById('audio-player');
    const audioSource = document.getElementById('audio-source');
    const playIcon = document.getElementById('play-icon');
    const songTitle = document.getElementById('song-title');
    const songArtist = document.getElementById('song-artist');

    function togglePlay() {
      if (audioPlayer.paused) {
        audioPlayer.play();
        playIcon.classList.remove('fa-play');
        playIcon.classList.add('fa-pause');
        hasUserPlayed = true;
      } else {
        audioPlayer.pause();
        playIcon.classList.remove('fa-pause');
        playIcon.classList.add('fa-play');
      }
    }

    function nextTrack(autoPlay = true) {
      currentSongIndex = (currentSongIndex + 1) % songs.length;
      loadTrack(autoPlay);
    }

    function previousTrack(autoPlay = true) {
      currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
      loadTrack(autoPlay);
    }

    function loadTrack(autoPlay = true) {
      const song = songs[currentSongIndex];
      audioSource.src = song.file;
      songTitle.textContent = song.title;
      songArtist.textContent = song.artist;

      const wasPlaying = !audioPlayer.paused;
      audioPlayer.load();

      playIcon.classList.remove('fa-pause');
      playIcon.classList.add('fa-play');

      if (autoPlay && wasPlaying) {
        audioPlayer.play();
        playIcon.classList.remove('fa-play');
        playIcon.classList.add('fa-pause');
      }
    }

    // Event saat lagu selesai
    audioPlayer.addEventListener('ended', function () {
      nextTrack(true);
    });

    // Load lagu pertama saat halaman dimuat (tanpa play)
    loadTrack(false);

    // Biar bisa dipanggil dari tombol HTML
    window.togglePlay = togglePlay;
    window.nextTrack = nextTrack;
    window.previousTrack = previousTrack;
  });
</script>