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


?>

<style>
  .login-box {
    padding-top: 8% !important;
    width: 360px;
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
  .login-title {
    display: block;
    font-size: 25px;
    font-weight: 700;
    text-align: center;
    margin: 0 0 6px;
    padding: 0 0 10px;
    border-bottom: 1px solid currentColor;
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
      width: 95%;
    }
  }
</style>

<div style="padding-top: 5%;" class="login-box">
  <div class="card">
    <div class="login-logo">
      <img src="img/favicon.png" alt="MIKHMON Logo">
    </div>
    <div class="card-body">
      <center>
      <form autocomplete="off" action="" method="post">
      <table class="table" style="width:100%">
        <tr>
          <td class="align-middle text-center">
            <span class="login-title">MIKHMON</span>
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <input style="width: 100%; height: 35px; font-size: 16px;" class="form-control" type="text" name="user" id="_username" placeholder="Username" required="1" autofocus>
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <input style="width: 100%; height: 35px; font-size: 16px;" class="form-control" type="password" name="pass" placeholder="Password" required="1">
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <input style="width: 100%; margin-top:20px; height: 35px; font-weight: 400; font-size: 17px;" class="btn-login bg-primary pointer" type="submit" name="login" value="Login">
          </td>
        </tr>
        <tr>
          <td class="align-middle text-right" style="padding-bottom: 0;">
            <a class="login-forgot" href="./admin.php?id=forgot">Lupa Password?</a>
          </td>
        </tr>
        <?php if (!empty($error)) { ?>
        <tr>
          <td class="align-middle text-center">
            <?= $error; ?>
          </td>
        </tr>
        <?php } ?>
      </table>
      </form>
      </center>
    </div>
  </div>
</div>

</body>
</html>
