<?= \Config\Services::validation()->listErrors(); ?>

<section>
  <table>
    <tr>
      <td><label for="email"> Email </label></td>
      <td><input type="email" name="email" id="email"/></td>
    </tr>

    <tr>
      <td><label for="username"> Username </label></td>
      <td><input type="text" name="username" id="username"/></td>
    </tr>

    <tr>
      <td><label for="name"> Name </label></td>
      <td><input type="text" name="name" id="name"/></td>
    </tr>

    <tr>
      <td><label for="password"> Password </label></td>
      <td><input type="password" name="password" id="password"/></td>
    </tr>

    <tr>
      <td><label for="cpassword"> Confirm Password </label></td>
      <td><input type="password" name="cpassword" id="cpassword"/></td>
    </tr>

    <tr>
      <td></td>
      <td><button onclick="doRegister()"> Signup! </button></td>
    </tr>
  </table>
</section>