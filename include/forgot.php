<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();

$error = '';

if (isset($_POST['resetpass'])) {
  $user = trim($_POST['user']);
  $newpass = $_POST['newpass'];
  $confirmpass = $_POST['confirmpass'];

  if ($user === '' || $newpass === '' || $confirmpass === '') {
    $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Semua field wajib diisi.</div>';
  } elseif ($user !== $useradm) {
    $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Username tidak sesuai.</div>';
  } elseif (strlen($newpass) < 4) {
    $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Password minimal 4 karakter.</div>';
  } elseif ($newpass !== $confirmpass) {
    $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Konfirmasi password tidak sama.</div>';
  } else {
    $spassadm = encrypt($newpass);
    $configfile = './include/config.php';
    $content = file_get_contents($configfile);
    $cari = 'mikhmon>|>' . $passadm;
    $ganti = 'mikhmon>|>' . $spassadm;
    if ($content === false || strpos($content, $cari) === false) {
      $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Gagal mengubah password.</div>';
    } else {
      $saved = file_put_contents($configfile, str_replace($cari, $ganti, $content));
      if ($saved !== false) {
        echo "<script>window.location='./admin.php?id=login&reset=1'</script>";
        exit;
      }
      $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> File konfigurasi tidak dapat ditulis.</div>';
    }
  }
}
?>

<style>
  .login-box {
    padding-top: 8% !important;
    width: min(360px, calc(100% - 48px));
    max-width: 360px;
    margin-left: auto;
    margin-right: auto;
    box-sizing: border-box;
  }
  .login-box .card {
    position: relative;
    overflow: visible;
    margin: 48px 0 0 0;
    border-radius: 16px;
  }
  .login-logo {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-50%, -58%);
    z-index: 2;
    line-height: 0;
    padding: 3px;
    background-color: inherit;
    border-radius: 20px;
  }
  .login-logo img {
    width: 80px;
    height: 80px;
    display: block;
    border-radius: 17px;
    box-shadow: none;
  }
  .login-box .card-body {
    padding: 52px 14px 8px;
    margin-bottom: 0;
  }
  .login-forgot {
    display: block;
    text-align: right;
    padding: 4px 0 0;
    color: #20a8d8;
    text-decoration: none;
    font-size: 14px;
  }
  .login-forgot:hover {
    text-decoration: underline;
  }
  @media (max-width: 576px) {
    .login-box {
      width: min(260px, calc(100% - 72px));
      max-width: 260px;
      margin-left: auto;
      margin-right: auto;
    }
  }
</style>

<div style="padding-top: 5%;" class="login-box">
  <div class="card">
    <div class="login-logo">
      <img src="img/favicon.png" alt="MIKHMON Logo">
    </div>
    <div class="card-body">
      <div class="text-center">
      <span style="font-size: 25px; margin: 10px;">Lupa Password</span>
      </div>
      <center>
      <form autocomplete="off" action="" method="post">
      <table class="table" style="width:100%">
        <tr>
          <td class="align-middle text-center">
            <input style="width: 100%; height: 35px; font-size: 16px;" class="form-control" type="text" name="user" placeholder="Username" required="1" autofocus>
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <input style="width: 100%; height: 35px; font-size: 16px;" class="form-control" type="password" name="newpass" placeholder="Password Baru" required="1">
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <input style="width: 100%; height: 35px; font-size: 16px;" class="form-control" type="password" name="confirmpass" placeholder="Konfirmasi Password" required="1">
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <button style="width: 100%; margin-top:20px; height: 35px; font-weight: bold; font-size: 17px; border: none;" class="btn-login bg-primary pointer" type="submit" name="resetpass" value="Simpan Password">Simpan Password</button>
          </td>
        </tr>
        <tr>
          <td class="align-middle text-right">
            <a class="login-forgot" href="./admin.php?id=login">Kembali ke Login</a>
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <?= $error; ?>
          </td>
        </tr>
      </table>
      </form>
      </center>
    </div>
  </div>
</div>

</body>
</html>
