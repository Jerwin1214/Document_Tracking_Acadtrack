<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Role | Acadtrack</title>
  <link href="https://fonts.googleapis.com/css2?family=Marcellus+SC&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Marcellus SC', serif;
    }

    a {
      text-decoration: none;
    }

    body, html {
      height: 100%;
      width: 100%;
    }

    body {
      background: url('{{ asset('images/BGpicture.png') }}') no-repeat center center fixed;
      background-size: cover;
    }

    .overlay {
      height: 100%;
      width: 100%;
      background: rgba(0, 0, 0, 0.3);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      background: rgba(255, 255, 255, 0.384);
      padding: 40px 30px;
      border-radius: 20px;
      text-align: center;
      width: 320px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }

    .card img {
      width: 100px;
      margin-bottom: 10px;
      border-radius: 50px;
    }

    .card h2 {
      font-size: 22px;
      margin-bottom: 20px;
      color: #2c3e50;
    }

    .btn {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      font-size: 15px;
      border: none;
      border-radius: 50px;
      background: #295c3b;
      color: #fff;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #1e4a30;
    }

    .header {
      position: absolute;
      top: 20px;
      left: 20px;
      color: white;
      display: flex;
      align-items: center;
    }

    .header img {
      height: 60px;
      margin-right: 15px;
      border-radius: 50px;
    }

    .header h1 {
      font-size: 20px;
      line-height: 1.2;
    }

    .header p {
      margin: 0;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="header">
    <img src="{{ asset('logo.jpg') }}" alt="Lyceum Logo">
    <div>
      <h1>ACADTRACK DIGITAL TRACKING SYSTEM</h1>
      <p>Lyceum of Lal-lo</p>
    </div>
  </div>

  <div class="overlay">
    <div class="card">
      <img src="{{ asset('logo.jpg') }}" alt="School Logo">
      <h2>ACADTRACK</h2>

      <!-- ✅ Clean role selection links with required route parameters -->
        {{-- <a href="{{ route('select.role.set', ['role' => 'student']) }}" class="btn">Student</a>
        <a href="{{ route('select.role.set', ['role' => 'teacher']) }}" class="btn">Teacher</a> --}}
        <a href="{{ route('select.role.set', ['role' => 'admin']) }}" class="btn">Administrator</a>
    </div>
  </div>
</body>
</html>
