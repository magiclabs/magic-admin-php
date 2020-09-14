<!DOCTYPE html>
<html>
  <head>
    <title>Magic Hello World 🌎</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <!-- 1️⃣ Install Magic SDK -->
    <script src="https://cdn.jsdelivr.net/npm/magic-sdk/dist/magic.js"></script>
    <script>
      /* 2️⃣ Initialize Magic Instance */
      const magic = new Magic("pk_test_07A67CC539DD272F");

      /* 3️⃣ Implement Render Function */
      const render = async () => {
        const isLoggedIn = await magic.user.isLoggedIn();
        /* Show login form if user is not logged in */
        let html = `
          <h1>Please sign up or login</h1>
          <form onsubmit="handleLogin(event)">
            <input type="email" name="email" required="required" placeholder="Enter your email" />
            <button type="submit">Send</button>
          </form>
        `;
        if (isLoggedIn) {
          magic_user_login()
        }
        document.getElementById("app").innerHTML = html;
      };

      /* 4️⃣ Implement Login Handler */
      const handleLogin = async e => {
        e.preventDefault();
        const email = new FormData(e.target).get("email");
        if (email) {
          /* One-liner login 🤯 */
          await magic.auth.loginWithMagicLink({ email });
          render();
        }
      };

      /* 5️⃣ Implement Logout Handler */
      const handleLogout = async () => {
        //await magic.user.logout();
        //render();
        magic_user_logout();
      };

      const magic_user_login = async () => {

        document.getElementById("spin").style.display = "block";
        document.getElementById("app").style.opacity = "0";

        /* Get user metadata including email */
        const userMetadata = await magic.user.getMetadata();
        var userEmail = userMetadata.email;
        var didToken = await magic.user.getIdToken();

        const data = 'type=login&email=' + userEmail;

        const xhr = new XMLHttpRequest();
        xhr.withCredentials = true;

        xhr.addEventListener('readystatechange', function() {
          if (this.readyState === this.DONE) {
            //console.log(this.responseText);
            var resp = JSON.parse(this.responseText);

            if (resp.success == true) {
              html = `
                <h1>Current user: ${resp.user_info.data.email}</h1>
                <button onclick="handleLogout()">Logout</button>
              `;
              document.getElementById("app").innerHTML = html;
            } else {
              html += `
              <p>
                ${resp}
              </p>`;
              document.getElementById("app").innerHTML = html;
            }

            document.getElementById("spin").style.display = "none";
            document.getElementById("app").style.opacity = "1";
          }
        });

        xhr.open('POST', 'http://localhost/magic_test/user-login.php', true);
        xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('authorization', 'Bearer ' + didToken);
        xhr.send(data);
      }

      const magic_user_logout = async () => {

        document.getElementById("spin").style.display = "block";
        document.getElementById("app").style.opacity = "0";
        


        /* Get user metadata including email */
        const userMetadata = await magic.user.getMetadata();
        var userEmail = userMetadata.email;
        var didToken = await magic.user.getIdToken();

        const data = 'type=logout&email=' + userEmail;

        const xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        xhr.addEventListener('readystatechange', function() {
          if (this.readyState === this.DONE) {
            //console.log(this.responseText);
            var resp = JSON.parse(this.responseText);

            if (resp.success == true) {
              html = `
                <h1>Please sign up or login</h1>
                <form onsubmit="handleLogin(event)">
                  <input type="email" name="email" required="required" placeholder="Enter your email" />
                  <button type="submit">Send</button>
                </form>
              `;
              document.getElementById("app").innerHTML = html;
            } else {
              html += `
              <p>
                ${resp}
              </p>`;
              document.getElementById("app").innerHTML = html;
            }

            document.getElementById("spin").style.display = "none";
            document.getElementById("app").style.opacity = "1";
          }
        });

        xhr.open('POST', 'http://localhost/magic_test/user-logout.php', true);
        xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('authorization', 'Bearer ' + didToken);
        xhr.send(data);
      }


    </script>
  </head>
  <body onload="render()">
    <div id="app">Loading...</div>
    <div id="spin"></div>
  </body>
</html>
