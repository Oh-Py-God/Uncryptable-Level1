<?= \Config\Services::validation()->listErrors(); ?>

<script>
  $( document ).ready(function() {
    alert("On dashboard");
    if (Object.keys(localStorage).indexOf('token') < 0) {
      alert("Session token does not exist");
      document.location = window.location.protocol + "//" + window.location.host;
    }
  });
</script>

<h3> Welcome to <?= $appname ?> </h3>

<?php if ($is_admin == false) : ?>
  <p> You are a normal user, your journey ends here... </p>
  <p> You should logout and try again, or just give up! </p>
  <br/>
  <input type="submit" name="verify" value="Logout" onclick="doLogout()" />


<?php else : ?>
  <p> Congrats! You made it to this part of the page. If you are clever enough, the next step should be a cake walk... </p> 
  <p> Try a key combination here and remember that Bruteforce would get you nowhere. Totally your choice! </p>

  <section>
    <table>
      <tr>
        <td><label for="key"> Key </label></td>
        <td><input type="input" name="key" size="50" id="key"/></td>
      </tr>

      <tr>
        <td><input type="submit" name="verify" value="Logout" onclick="doLogout()" /></td>
        <td><input type="submit" name="verify" value="Verify" onclick="getFlag()" /></td>
      </tr>
      
    </table>
  </section>
<?php endif ?>