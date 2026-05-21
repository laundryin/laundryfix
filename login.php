<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - dilaundryin ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #fdf2f8 0%, #e0f2fe 100%); height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 25px; box-shadow: 0 10px 25px rgba(236, 72, 153, 0.1); width: 100%; max-width: 400px; text-align: center; }
        .btn-pink { background: linear-gradient(90deg, #3b82f6, #ec4899); border: none; color: white; border-radius: 12px; padding: 12px; transition: 0.3s; font-weight: 600; }
        .btn-pink:hover { opacity: 0.9; transform: translateY(-2px); color: white; }
        .form-control { border-radius: 10px; padding: 10px; border: 1px solid #fce7f3; }
        .text-gradient { background: linear-gradient(90deg, #3b82f6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="londriin.jpeg" style="width: 80px; border-radius: 15px; margin-bottom: 15px;">
        <h4 class="fw-bold mb-4 text-gradient">dilaundryin <span style="color: #ec4899;">ERP</span></h4>
        
        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "gagal"){
                echo "<div class='alert alert-danger py-2 small'>Username atau Password Salah!</div>";
            } else if($_GET['pesan'] == "logout"){
                echo "<div class='alert alert-success py-2 small'>Berhasil Logout.</div>";
            } else if($_GET['pesan'] == "belum_login"){
                echo "<div class='alert alert-warning py-2 small'>Silahkan Login dulu bos!</div>";
            }
        }
        ?>
        
        <form action="cek_login.php" method="POST">
            <div class="mb-3 text-start">
                <label class="small fw-bold text-muted">Username</label>
                <input type="text" name="username" class="form-control" placeholder="admin" required>
            </div>
            <div class="mb-4 text-start">
                <label class="small fw-bold text-muted">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-pink w-100 shadow-sm">MASUK SEKARANG</button>
        </form>
    </div>
</body>
</html>