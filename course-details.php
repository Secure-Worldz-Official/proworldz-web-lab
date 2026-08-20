<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Course Details</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="course-details.css">
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>

<section class="hero">
  <div class="hero-left">
    <h1 id="title"></h1>
    <p id="heroText"></p>
    <button class="enroll-btn">Enroll Now</button>
    <div class="meta">
      <span id="duration"></span>
      <span id="price"></span>
    </div>
  </div>

  <div class="hero-right">
    <img id="heroImg" alt="dwwkdh" loading="lazy"> 
  </div>
</section>

<section>
  <h2>Things You Master</h2>
  <div id="master"></div>
</section>

<section>
  <h2>Topics Covered</h2>
  <ul id="topics"></ul>
</section>

<section>
  <h2>Benefits</h2>
  <div id="benefits"></div>
</section>

<section>
  <h2>Mentor</h2>
  <h3 id="mentorName"></h3>
  <p id="mentorDesc"></p>
</section>

<script src="course-data.js"></script>
<script>
const key = new URLSearchParams(location.search).get("course");
const c = courses[key];

if (!c) {
  document.body.innerHTML =
    "<h1 style='color:white;text-align:center'>Course Not Found</h1>";
  throw new Error("Invalid course");
}

title.innerText = c.title;
heroText.innerText = c.heroText;
price.innerText = c.price;
duration.innerText = c.duration;
heroImg.src = c.image;

c.master.forEach(i => master.innerHTML += `<div>${i}</div>`);
c.topics.forEach(i => topics.innerHTML += `<li>${i}</li>`);
c.benefits.forEach(i => benefits.innerHTML += `<div>${i}</div>`);

mentorName.innerText = c.mentor.name;
mentorDesc.innerText = c.mentor.desc;
</script>

</body>
</html>
