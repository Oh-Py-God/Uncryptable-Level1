<?= \Config\Services::validation()->listErrors(); ?>

<section>
  <!--form method="post" id="loginForm">
  <= csrf_field() ?-->

  <table>
    <tr>
      <td><label for="username"> Username </label></td>
      <td><input type="input" name="username" id="username"/></td>
    </tr>

    <tr>
      <td><label for="password"> Password </label></td>
      <td><input type="password" name="password" id="password"/></td>
    </tr>
    
    <tr>
      <td></td>
      <td><button onclick="doLogin()"> Login </button></td>
    </tr>

    <tr>
      <td> New User/Forgot Password? </td>
      <td><button onclick="goToSignup()"> Signup </button></td>
    </tr>
    
  </table>
  <!--/form-->
</section>