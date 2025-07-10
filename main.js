<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Luxenter Contracting - Dubai</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <header>
    <img src="images/luxenter-logo.png" alt="Luxenter Contracting" class="logo" />
    <select id="language-switcher">
      <option value="en">English</option>
      <option value="fr">Français</option>
      <option value="ar">العربية</option>
    </select>
    <nav>
      <ul>
        <li><a href="#" data-key="home">Home</a></li>
        <li><a href="#" data-key="about">About</a></li>
        <li><a href="#" data-key="services">Services</a></li>
        <li><a href="#" data-key="projects">Projects</a></li>
        <li><a href="#" data-key="contact">Contact</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h1 data-key="welcome">Welcome to Luxenter Contracting</h1>
    <p data-key="intro">We deliver excellence in construction across Dubai.</p>
  </main>

  <script>
    const switcher = document.getElementById('language-switcher');

    switcher.addEventListener('change', (e) => {
      const lang = e.target.value;
      fetch(`lang/${lang}.json`)
        .then(response => response.json())
        .then(data => {
          document.querySelectorAll('[data-key]').forEach(element => {
            const key = element.getAttribute('data-key');
            if (data[key]) {
              element.textContent = data[key];
            }
          });

          if (lang === 'ar') {
            document.body.style.direction = 'rtl';
            document.body.style.textAlign = 'right';
          } else {
            document.body.style.direction = 'ltr';
            document.body.style.textAlign = 'left';
          }
        })
        .catch(err => console.error('Translation error:', err));
    });
  </script>
</body>
</html>
