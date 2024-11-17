<?php
$file    = 'db/database.json';
if (!file_exists($file)) {
    echo "<h1>Jalankan admin terlebih dahulu</h1>";
    die;
}
$json     = file_get_contents($file);
$db        = json_decode($json, true);
$showDb    = $db;
unset($showDb['akses']);

// get logo file
$dirLogo    = 'logo/';
$filesLogo    = array_diff(scandir($dirLogo), array('.', '..', 'Thumbs.db'));
$filesLogo    = array_values($filesLogo); //re index
$logo        = $filesLogo[0];

//wallpaper slideshow setup
$wallpaper_timer    = $db['timer']['wallpaper'] * 1000;
$dir    = 'wallpaper/';
$files    = array_diff(scandir($dir), array('.', '..', 'Thumbs.db'));

$info_timer            = $db['timer']['info']         * 1000;    //detik

// get name
$tempat = explode(" ", $db['setting']['nama']);
$nama = array_slice($tempat, 1);
$nama = implode(" ", $nama);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Clock</title>
    <link rel="stylesheet" href="./css/style_new.css">
</head>

<body class="overflow-hidden">
    <header class="fixed z-[10] flex w-screen">
        <div id="left-header" class="w-[66vw] h-[120px] bg-[#3D712F] rounded-br-[74px] flex items-center px-[24px] z-10">
            <img src="logo/<?= $logo ?>" alt="" class="w-[100px]">
            <div class="flex flex-col pl-[2rem]">
                <h2 class="font-Montagu font-bold text-white text-[2.4rem]"><span class="text-[#A8BF02]"><?= $tempat[0] ?> </span><?= $nama ?></h2>
                <!-- <p class="text-white font-thin text-[16px]">PERUMAHAN CENDANA RT 2 RW 36 KEL.BELIAN KEC.BATAM KOTA - KOTA BATAM</p> -->
            </div>
        </div>
        <div id="right-header" class="w-[35vw] h-[77px] bg-[#000000bf] flex items-center pr-[24px] justify-end absolute right-0">
            <p class="font-Montserrat text-white font-semibold text-[1.6rem] dayDate"><span id="day">Rabu</span>, <span class="text-[#E0E0E0]" id="dateID">15 Mei 2024</span> / <span class="text-[#E0E0E0]" id="dateAR">7 Dhu al-Qi'dah 1445</span></p>
        </div>
    </header>
    <div id="slideshow" class="absolute w-screen h-screen" data-component="slideshow">
        <div role="list">
            <?php
            foreach ($files as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if ($ext == 'mp4') {
                    echo "<div class='slide' data-duration=''>
                        <video class='w-screen' src='wallpaper/$file' muted></video>
                    </div>";
                } else {
                    echo "<div class='slide' data-duration=''>
                        <img class='w-screen' src='wallpaper/$file' alt=''>
                    </div>";
                }
            }
            ?>
        </div>
    </div>
    <main class="w-screen h-screen">
        <div class="overlay bg-black w-screen h-screen opacity-[0.4]"></div>
        <div class="absolute top-[27%] right-0">
            <div id="slideshow-info">
                <?php
                foreach ($db['info'] as $info) {
                    if ($info[3]) {
                        echo '<div class="flex float-end max-w-[70%] flex-col slide p-10 w-screen bg-[#00000026] text-end hidden">';
                        echo "<h1 class='text-white text-[56px] uppercase font-semibold'>$info[0]</h1>";
                        echo "<h1 class='mt-[12px] text-white text-[32px] italic'>$info[1]</h1>";
                        echo "<h1 class='text-white text-[22px]'>$info[2]</h1>";
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
        </div>
    </main>
    <footer class="absolute bottom-0 w-screen h-[280px] flex z-[9999]">
        <div id="current-time" class="w-[25vw] bg-[#3D712F] h-[100%] flex flex-col text-[#FFB800] font-Montserrat items-center justify-center pt-[1.2%]">
            <p class="text-[24px] font-medium">Waktu Saat Ini</p>
            <h1 class="font-bold text-[64px]" id="currentTime">00:00:00</h1>
            <img class="w-[75%] mt-[-1.2%]" src="./img/clock-line.png" alt="">
        </div>
        <div class="right-wrapper w-[75vw] flex flex-col font-Montserrat">
            <div class="prayerTimes grid grid-flow-col auto-cols-auto divide-x divide-neutral-900/[.3]">
            </div>
            <marquee class="bg-[#000]/[.80] h-[70px] text-white flex items-center uppercase text-[22px]">
                SELAMAT DATANG DI MASJID DARUSSALAM • HARAP MEMATIKAN ATAU MENONAKTIFKAN TELEPON GENGGAM AGAR TIDAK MENGGANGU KEKHUSYUKAN IBADAH •
                <?php
                foreach ($db['running_text'] as $runningText) {
                    echo $runningText . " • ";
                }
                ?>
            </marquee>
        </div>
    </footer>
    <script src="./js/tailwind.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <script src="js/PrayTimes.js"></script>
    <script src="js/hijricalendar.js"></script>
    <script>
        //slideshow image and video
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('#slideshow .slide');

            slides.forEach(slide => {
                const img = slide.querySelector('img');
                const video = slide.querySelector('video');

                if (img) {
                    slide.setAttribute('data-duration', <?= $wallpaper_timer ?>);
                } else if (video) {
                    slide.setAttribute('data-duration', video.duration * 1000 - 1000);
                }
            });
        });

        function initSlideShow(slideshow) {
            var slides = document.querySelectorAll(`#${slideshow.id} [role="list"] .slide`);
            var index = 0;

            slides[index].classList.add('active');
            slides[index].classList.add('fade-in');

            function showNextSlide() {
                // Handle video reset on the current slide
                const currentSlideVideo = slides[index].querySelector('video');
                if (currentSlideVideo) {
                    $('#slideshow-info').fadeIn();
                    setTimeout(() => {
                        currentSlideVideo.pause();
                        currentSlideVideo.currentTime = 0;
                    }, 1000);
                }

                // Remove active classes from the current slide
                slides[index].classList.remove('active');
                slides[index].classList.remove('fade-in');
                slides[index].classList.add('fade-out');

                // Move to the next slide
                index++;
                if (index === slides.length) index = 0;

                // Add active classes to the next slide
                slides[index].classList.remove('fade-out');
                slides[index].classList.add('active');
                slides[index].classList.add('fade-in');

                // Handle video play on the next slide
                const nextSlideVideo = slides[index].querySelector('video');
                if (nextSlideVideo) {
                    $('#slideshow-info').fadeOut();
                    nextSlideVideo.play();
                }

                // Get the duration for the next slide
                const nextSlideDuration = parseInt(slides[index].getAttribute('data-duration'), 10);

                setTimeout(showNextSlide, nextSlideDuration);
            }

            // Start the slideshow
            const initialDuration = parseInt(slides[index].getAttribute('data-duration'), 10);
            setTimeout(showNextSlide, initialDuration);
        }

        document.addEventListener('DOMContentLoaded', () => {
            var slideshows = document.querySelectorAll('[data-component="slideshow"]');
            slideshows.forEach(initSlideShow);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('#slideshow-info .slide');
            let currentIndex = 0;
            const duration = <?= $info_timer ?>; // Duration for each slide in milliseconds

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    if (i === index) {
                        setTimeout(() => {

                            $(slide).fadeIn() // Show the current slide
                        }, 800);
                    } else {
                        $(slide).fadeOut() // Hide the other slides
                    }
                });
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                showSlide(currentIndex);
                setTimeout(nextSlide, duration);
            }

            // Initialize the slideshow
            showSlide(currentIndex);
            setTimeout(nextSlide, duration);
        });
    </script>

    <script>
        // init prayTimes JS
        let format = '24h'
        let lat = <?= $db['setting']['latitude'] ?>;
        let lng = <?= $db['setting']['longitude'] ?>;
        let timeZone = <?= $db['setting']['timeZone'] ?>;
        let dst = <?= $db['setting']['dst'] ?>;

        <?php
        // adjust prayTimes (metode perhitungan)
        $prayTimesAdjust = [];
        if ($db['prayTimesMethod'] == '0') {
            foreach ($db['prayTimesAdjust'] as $k => $v) {
                if ($v != '') $prayTimesAdjust[$k] = $v;
            }
            echo "var prayTimesAdjust =	$.parseJSON('" . stripslashes(str_replace("`", "\\`", json_encode($prayTimesAdjust))) .
                "');\n";
            echo "prayTimes.adjust(prayTimesAdjust);\n";
        } else {
            echo "prayTimes.setMethod('" . $db['prayTimesMethod'] .
                "');\n";
        }

        // tune prayTimes (penambahan atau pengurangan menit pada jadwal sholat)
        $prayTimesTune = [];
        foreach ($db['prayTimesTune'] as $k => $v) {
            if ($v != '0') $prayTimesTune[$k] = $v;
        }
        if (count($prayTimesTune) > 0) {
            echo "var prayTimesTune =	$.parseJSON('" . stripslashes(str_replace("`", "\\`", json_encode($prayTimesTune))) .
                "');\n";
            echo "prayTimes.tune(prayTimesTune);\n";
        } ?>

        $(document).ready(function() {

            var currentPrayer = '';

            const updateClock = () => {
                let now = moment().locale('id');
                $('#day').text(now.format('dddd'));
                $('#dateID').text(now.format('DD MMMM YYYY'));
                $('#currentTime').text(now.format('HH:mm:ss'));
                $('#dateAR').text(writeIslamicDate());
            }

            const getJadwal = (jadwalDate) => {
                let times = prayTimes.getTimes(jadwalDate, [lat, lng], timeZone, dst, format);
                return times;
            }

            const addMinutesToTime = (timeString, minutesToAdd) => {
                let [hours, minutes] = timeString.split(':').map(Number);
                let date = new Date();

                date.setHours(hours, minutes, 0, 0);
                date.setMinutes(date.getMinutes() + minutesToAdd);

                let newHours = date.getHours().toString().padStart(2, '0');
                let newMinutes = date.getMinutes().toString().padStart(2, '0');

                return `${newHours}:${newMinutes}`;
            }

            const displaySchedule = (date) => {
                let jadwalHariIni = getJadwal(date);
                let now = moment().format('HH:mm');

                // check what is current prayer time and give active class
                if (now < jadwalHariIni.fajr) {
                    currentPrayer = 'fajr';
                } else if (now > addMinutesToTime(jadwalHariIni.fajr, 120) && now > addMinutesToTime(jadwalHariIni.dhuhr, -30) && now < addMinutesToTime(jadwalHariIni.asr, -29)) {
                    currentPrayer = 'dhuhr';
                } else if (now > addMinutesToTime(jadwalHariIni.asr, -30) && now < addMinutesToTime(jadwalHariIni.maghrib, -29)) {
                    currentPrayer = 'asr';
                } else if (now > addMinutesToTime(jadwalHariIni.maghrib, -30) && now < addMinutesToTime(jadwalHariIni.isha, -29)) {
                    currentPrayer = 'maghrib';
                } else if (now > addMinutesToTime(jadwalHariIni.isha, -30) && now < addMinutesToTime(jadwalHariIni.fajr, -29)) {
                    currentPrayer = 'isha';
                }

                let prayName = <?= json_encode($db['prayName']) ?>;
                $('.prayerTimes').html(
                    Object.keys(jadwalHariIni).filter(key => key in prayName).map((key, index) => {
                        let active = '';
                        if (key == currentPrayer) {
                            active = 'active';
                        }
                        return `<div class="bg-[#3D712F] h-[210px] flex flex-col justify-center text-white ${active}">
                            <p class="font-normal text-[24px] pl-[20%]">${prayName[key]}</p>
                            <h1 class="font-bold text-[64px] text-center">${jadwalHariIni[key]}</h1>
                        </div>`
                    }).join('')
                )
            }

            const displayCountdownToAdzan = () => {

            }

            var todayDate = moment().format('YYYY-MM-DD');

            updateEverySec = () => {
                setInterval(() => {
                    if (todayDate < moment().format('YYYY-MM-DD')) {
                        displaySchedule(moment().add(1, 'days').toDate());
                        todayDate = moment().format('YYYY-MM-DD');
                    }
                    updateClock();
                }, 1000);
            }

            // run instantly
            updateEverySec();
            updateClock();
            displaySchedule(moment().toDate());
        });
    </script>
</body>

</html>