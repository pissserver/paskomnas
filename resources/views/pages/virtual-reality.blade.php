@extends('layouts.user_type.auth')

@section('title', 'Virtual Reality Paskomnas || PISS')

@section('content')

<div class="section position-relative transform-scale-0 d-flex justify-content-center align-items-center"
  style="height: 100vh; overflow: hidden;">
  <div class="container">
    <div class="row">
      <div class="col-lg-1 col-md-1 pt-5 pt-lg-0 ms-lg-5 text-center">
        <a href="javascript:;" class="avatar avatar-md border-0 mb-2" data-bs-toggle="tooltip" data-bs-placement="left"
          title="{{ Auth::user()->name }}">
          <img class="border-radius-lg" alt="Image placeholder"
            src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('assets/img/default-profile.jpg') }}">
        </a>
        <a href="{{ route('dashboard.index') }}" class="btn btn-white border-radius-lg p-2 mt-2"
          data-bs-toggle="tooltip" data-bs-placement="left" title="Dashboard">
          <i class="fas fa-home p-2"></i>
        </a>
        <a href="{{ route('user.index') }}" class="btn btn-white border-radius-lg p-2" data-bs-toggle="tooltip"
          data-bs-placement="left" title="Profil">
          <i class="fas fa-User p-2"></i>
        </a>
        <button id="change-bg-btn" class="btn btn-white border-radius-lg p-2" type="button" data-bs-toggle="tooltip"
          data-bs-placement="left" title="Ganti Background">
          <i class="fas fa-sync-alt p-2"></i>
        </button>
      </div>
      <div class="col-lg-8 col-md-11">
        <div class="d-flex">
          {{-- <div class="me-auto">
            <h1 class="display-1 font-weight-bold mt-n4 mb-0">28°C</h1>
            <h6 class="text-uppercase mb-0 ms-1">Cloudy</h6>
          </div> --}}
          <div class="me-auto">
            <h1 id="current-time" class="d-inline-block text-white display-1 font-weight-bold mt-n4 mb-0">--:--</h1>
            <h6 class="text-white text-uppercase mb-0 ms-1 d-inline-block">WIB</h6>
            <h6 class="text-white text-capitalize mb-0 ms-1"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;Sidotopo,
              Surabaya
            </h6>
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
                <div class="d-flex align-items-center">
                  <h6 class="mb-0 ms-3 me-5"><i class="fa fa-user-tag"></i></h6>
                  <h6 class="mb-0">Role <br>
                    <small class="text-secondary font-weight-normal text-capitalize">{{ Auth::user()->role }}</small>
                  </h6>
                </div>
                <hr class="horizontal dark">
                <div class="d-flex align-items-center">
                  <h6 class="mb-0 ms-3 me-5"><i class="fa fa-phone"></i></h6>
                  <h6 class="mb-0">No Telepon
                    <small class="text-secondary font-weight-normal">0{{ Auth::user()->phone }}</small>
                  </h6>
                </div>
                <hr class="horizontal dark">
                <div class="d-flex align-items-center">
                  <h6 class="mb-0 ms-3 me-5"><i class="fa fa-map-marker"></i></h6>
                  <h6 class="mb-0">Lokasi <br />
                    <small class="text-secondary font-weight-normal">{{ Auth::user()->location }}</small>
                  </h6>
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
                  <h5 class="text-white text-sm text-bold">Music&nbsp;&nbsp; <i class="fa fa-music"></i></h5>
                  <h5 class="text-white mb-0" id="song-title"></h5>
                  <p class="text-white text-sm" id="song-artist"></p>

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
                <div class="d-flex justify-content-evenly">
                  <p class="mb-0 mt-1"><i class="fa fa-envelope"></i></p>
                  <a href="{{ route('user.index') }}" class="ms-3 text-truncate" style="max-width: 200px;"
                    data-bs-toggle=" tooltip" data-bs-placement="top" title="Cek email anda">
                    {{ Auth::user()->email }}
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 mt-4 mt-sm-0">
            <div class="card bg-gradient-dark move-on-hover">
              <div class="card-body">
                <div class="d-flex">
                  <h5 class="mb-0 text-white">Daftar</h5>
                  <div class="ms-auto">
                    <h1 class="text-white text-end mb-0 mt-n2">{{ $totalUsers }}</h1>
                    <p class="text-sm mb-0 text-white text-center">Total</p>
                  </div>
                </div>
                <p class="text-white mb-0">Akun</p>
                <p class="mb-0 text-white">Pengguna</p>
              </div>
              <a href="javascript:;" class="w-100 text-center py-1" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Semua Pengguna">
                <i class="fas fa-chevron-down text-white"></i>
              </a>
            </div>
            <div class="card move-on-hover mt-4">
              <div class="card-body">
                <p class="mb-3"><i class="fa fa-users"></i>&nbsp;&nbsp;Semua Pengguna</p>
                <div class="avatar-group">
                  @foreach ($users as $user)
                  <a href="javascript:;" class="avatar avatar-sm rounded-circle me-1" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ $user->name }}">
                    <img alt="Image placeholder"
                      src="{{ $user->photo ? asset($user->photo) : asset('assets/img/default-profile.jpg') }}">
                  </a>
                  @endforeach
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
    // Ganti background
    const backgrounds = [
      "{{ asset('assets/img/vr-bg1.jpg') }}",
      "{{ asset('assets/img/vr-bg2.jpg') }}",
      "{{ asset('assets/img/vr-bg3.jpg') }}",
      "{{ asset('assets/img/vr-bg4.jpg') }}"
    ];
    let currentIndex = 0;

    document.getElementById("change-bg-btn").addEventListener("click", function () {
      const wrapper = document.getElementById("background-wrapper");
      currentIndex = (currentIndex + 1) % backgrounds.length;
      wrapper.style.backgroundImage = `url('${backgrounds[currentIndex]}')`;
    });

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