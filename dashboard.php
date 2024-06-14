<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Me Cook</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-decoration: none;
            transition: all .2s linear;
        }
        
        body {
            background: #e2e5de;
        }
        
        .recipe-container {
            width: 100%;
            margin-right: auto;
            padding: 3rem;
        }
        
        .recipe-container .recipe-title {
            font-size: 3.5rem;
            color: #444;
            margin-bottom: 3rem;
            text-transform: uppercase;
        }
        
        .recipe-container .recipe-container2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(30rem, 1fr));
            gap: 2rem;
        }
        
        .recipe-container .recipe-container2 .recipe_preview {
            text-align: center;
            padding: 3rem 2rem;
            background: #b2beb5;
            box-shadow: 0.5rem rgba(0, 0, 0, .1);
            border-radius: 30px;
            outline: .1rem solid #ccc;
            cursor: pointer;
        }
        
        .recipe-container .recipe-container2 .recipe_preview img {
            height: 20rem;
            border-radius: 30px;
            width: 25rem;
        }
        
        .recipe-container .recipe-container2 .recipe_preview:hover img {
            transform: scale(.9);
        }
        
        .recipe-container .recipe-container2 .recipe_preview h3 {
            padding: .2rem 0;
            margin-top: 2rem;
            font-size: 2.7rem;
            color: #444;
            background: #b2beb5;
        }
        
        .recipe-container .recipe-container2 .recipe_preview h3:hover {
            color: #27ae60;
        }
        
        .recipe-pop {
            position: fixed;
            top: 0;
            left: 0;
            min-height: 100vh;
            width: 100%;
            background: rgba(0, 0, 0, .8);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .recipe-pop .popup {
            display: none;
            padding: 2rem;
            text-align: center;
            background: #b2beb5;
            position: relative;
            margin: 2rem;
            width: 40rem;
            border-radius: 30px;
        }
        
        .recipe-pop .popup.active {
            display: inline-block;
        }
        
        .recipe-pop .popup img {
            height: 22rem;
            border-radius: 30px;
        }
        
        .recipe-pop .popup .fa-times {
            position: absolute;
            top: .5rem;
            right: .7rem;
            cursor: pointer;
            color: #444;
            font-size: 3rem;
            background-color: transparent;
        }
        
        .recipe-pop .popup .fa-times:hover {
            color: #fff;
            transform: rotate(90deg);
        }
        
        .recipe-pop .popup h3 {
            color: #444;
            background: #b2beb5;
            padding: .3rem 0;
            font-size: 3rem;
        }
        
        .recipe-pop .popup p {
            color: #444;
            background: #b2beb5;
            padding: .4rem 0;
            font-size: 1.7rem;
            text-align: left;
        }
        
        .slider {
            width: 100%;
            display: flex;
            overflow: hidden;
        }
        
        .slide {
            flex: 0 0 100%;
            max-width: 100%;
            transition: transform 2s;
        }
        
        .slide h2 {
            font-size: 2em;
            font-weight: bold;
        }
        
        .indicator {
            text-align: center;
        }
        
        .indicator span {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: lightgray;
            margin: 0 5px;
            cursor: pointer;
        }
        
        .indicator span.active {
            background-color: gray;
        }
        
        .recipe-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        
        .recipe-card {
            background-color: #f8f8f8;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 200px;
            height: 300px;
            /* Fixed height for card */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
        }
        
        .recipe-card img {
            max-height: 50%;
            /* Image takes up to half of the card */
            width: 100%;
            object-fit: cover;
        }
        
        .recipe-card h4 {
            padding: 10px;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50%;
            /* Name takes up the remaining half */
        }
        
        nav-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            background-color: #0cae87;
            height: 90px;
            color: white;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }
        /* Menu icon styling */
        
        .checkbtn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            color: white;
            font-size: 30px;
        }
        /* Logo styling */
        
        label.nav-logo {
            flex-grow: 1;
            text-align: center;
            font-size: 70px;
            font-weight: bold;
        }
        
        .fa-bars {
            background: transparent;
        }
        
        #check {
            display: none;
        }
    </style>
</head>

<body>

    <header>
        <nav-top>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <i class="fas fa-bars"></i>
            </label>
            <label class="nav-logo">HELP ME COOK</label>
        </nav-top>
    </header>

    <div class="recipe-container">
        <h3 class="recipe-title"> Resepi Terbaru </h3>

        <div class="slider" id="recipeSlider">
            <div class="slide">
                <h2>Recipe 1</h2>
                <img src="images/ayam asam pedas.jpeg" alt="Recipe 1">
            </div>
            <div class="slide">
                <h2>Recipe 2</h2>
                <img src="images/ayam kari.jpg" alt="Recipe 2">
            </div>
            <div class="slide">
                <h2>Recipe 3</h2>
                <img src="images/ayam kicap.png" alt="Recipe 3">
            </div>
            <div class="slide">
                <h2>Recipe 4</h2>
                <img src="images/ayam kunyit.jpg" alt="Recipe 4">
            </div>
        </div>

        <div class="indicator" id="indicator">
            <span class="active" data-slide="0"></span>
            <span data-slide="1"></span>
            <span data-slide="2"></span>
            <span data-slide="3"></span>
        </div>
    </div>

    <div class="recipe-list">
        <div class="recipe-card">
            <img src="images/ayam asam pedas.jpeg" alt="Ayam Asam Pedas">
            <h4>Ayam Asam Pedas</h4>
        </div>
        <div class="recipe-card">
            <img src="images/ayam kari.jpg" alt="Ayam Kari">
            <h4>Ayam Kari</h4>
        </div>
        <div class="recipe-card">
            <img src="images/ayam kicap.png" alt="Ayam Kicap">
            <h4>Ayam Kicap</h4>
        </div>
        <div class="recipe-card">
            <img src="images/ayam kunyit.jpg" alt="Ayam Kunyit">
            <h4>Ayam Kunyit</h4>
        </div>
    </div>

    <script>
        var slider = document.getElementById('recipeSlider');
        var slides = slider.getElementsByClassName('slide');
        var indicators = document.getElementById('indicator').getElementsByTagName('span');
        var index = 0;
        var interval;

        function rotateSlides() {
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.transform = 'translateX(' + (index * -100) + '%)';
            }
            for (var i = 0; i < indicators.length; i++) {
                indicators[i].classList.remove('active');
            }
            indicators[index].classList.add('active');
            index = (index + 1) % slides.length;
        }

        function goToSlide(slideIndex) {
            index = slideIndex;
            rotateSlides();
            clearInterval(interval);
            interval = setInterval(rotateSlides, 10000);
        }

        // Initialize slider
        for (var i = 0; i < indicators.length; i++) {
            indicators[i].addEventListener('click', function() {
                var slideIndex = parseInt(this.getAttribute('data-slide'));
                goToSlide(slideIndex);
            });
        }

        interval = setInterval(rotateSlides, 10000);
    </script>

</body>

</html>