<?php
    include("./session_start.php")
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script
      async
      src="https://www.googletagmanager.com/gtag/js?id=UA-180184444-1"
    ></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() {
        dataLayer.push(arguments);
      }
      gtag("js", new Date());

      gtag("config", "UA-180184444-1");
    </script>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masks by Tabitha</title>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
      crossorigin="anonymous"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Comfortaa&family=Playfair+Display:wght@500&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
      integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="../styles/mask_page.css" />
  </head>

  <body>
    <div
      id="cartDiv"
      class="d-none"
      style="
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 100000;
      "
    >
      <div
        style="
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          padding-top: 25px;
          padding-bottom: 25px;
        "
      >
        <button onclick="closeCart()" style="margin-bottom: 10px">
          Close Cart
        </button>
        <button onclick="postCart(cart)" style="margin-bottom: 10px">
          Send Cart
        </button>
        <div id="cartItemDiv"></div>
      </div>
    </div>

    <a id="back-btn" href="https://www.designsbytabitha.ca"> Back </a>

    <div id="account">
      <button
        id="accountBtn"
        onclick="window.location.href='https://www.designsbytabitha.ca/session.html'"
        style="
          position: fixed;
          right: 0;
          z-index: 998;
          top: 0;
          border: none;
          padding: 10px;
          margin-top: 10px;
          border-radius: 20px 0 0 20px;
        "
      >
        Account
      </button>
      <?php
            if($_SESSION["user"] != "") {
                
            } else {
                echo "<div style='display: none;'>"; } ?>
      <button
        id="viewCartBtn"
        onclick="viewCart()"
        style="
          position: fixed;
          right: 0;
          z-index: 998;
          top: 0;
          border: none;
          padding: 10px;
          margin-top: 60px;
          border-radius: 20px 0 0 20px;
        "
      >
        View Cart
      </button>
      <?php
        if($_SESSION["user"] != "") {
            
        } else {
            echo "</div>"; } ?>
    </div>

    <h1 class="main-header">Face Masks</h1>
    <hr class="header-hr" />

    <!-- Mask Description -->
    <!--
    <ul style="
    	max-width: 500px;
	margin: auto;
    ">
        <li>Machine Washable, Tumble Dry on low setting</li>
        <li>Made to Order</li>
        <li>3 Layers of Protection</li>
        <li>2 layers of 100% premium cotton - Main Fashion Fabric (outward layer) Lining solid Fabric (inner
        layer)</li>
        <li>3rd layer of non-woven moisture resistant fabric (interlining, sits between main fabric and lining fabric</li>
        <li>Non-woven bonded fabric is breathable and provides an extra barrier of protection
        <li>When a mask is worn for a lengthy time moisture can build up which can then harbour bacteria.</li>
        <li>This third layer of fabric keeps moisture from reaching the outside main fabric.</li> 
        • Contains a filter pocket for added protection
        • Nose guard with wire for a better fit - helps prevent glasses from fogging
        • Colour Coordinated thin elastic ear straps for optimal comfort
        <li>Logo’s Logos, names, and images can be added using Infusible Ink can be added for an additional fee</li> 
        Price:
        $15 for a 2 layer mask
        $20 for the 3 layer mask
        Sizing:
        Average: fits most women and teenagers
        Large: fits most men
        Youth 7 - 12 yrs
        Child 3 - 6 yrs
        Customized sizing available upon request
        Add-ons:
        • Customized logos
    </ul> 
    -->

    <br />

    <h3 style="text-align: center">Sizes</h3>
    <div class="size-table">
      <p>Average</p>
      <p>Large</p>
      <p class="no-wrap">Child (3-6yrs)</p>
      <p class="no-wrap">Youth (7-12yrs)</p>
    </div>

    <!-- <div class="made-2-order-msg">
        <p>All other sizes or out-of-stock items available made to order</p>
    </div> -->

    <div
      class="fade-in-container"
      style="
        z-index: 20000;
        width: 100vw;
        height: 100vh;
        position: absolute;
        background: white;
        text-align: center;
        display: none;
      "
    >
      <h1>LOADING...</h1>
    </div>

    <div id="mask-item-container" class="">
      <!-- EACH MASK ITEM APPENDED IN HERE -->
      <div id="overlay-container" class="overlay" style="display: none"></div>
    </div>

    <div id="go-top">
      <div>
        <i class="fas fa-arrow-up" style="font-size: 200%"></i>
      </div>
      <div>Top</div>
    </div>

    <a href="https://ko-fi.com/dbt/shop">
      <div id="Ko-fi-container">
        <p class="Ko-fi-text">
          Buy me a
          <br />
          <span class="Ko-fi-sub-header">Coffee</span>
        </p>
        <img class="Ko-fi-icon" src="images/icons/Ko-fi_small.png" />
      </div>
    </a>

    <a
      class="footer-container d-none"
      style="color: lightgray"
      href="https://www.linkedin.com/in/paulb-h/"
      target="blank"
    >
      <div class="footer">
        &copy; Web by Paul
        <i
          class="fas fa-link"
          style="margin-left: 5px; margin-top: 2px; font-size: 90%"
        ></i>
      </div>
    </a>

    <div id="myScriptContainer">
      <script
        src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
        crossorigin="anonymous"
      ></script>
      <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
      <script
        src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"
      ></script>
      <script
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"
      ></script>
      <script
        src="https://kit.fontawesome.com/301fc3fb33.js"
        crossorigin="anonymous"
      ></script>
      <script
        src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous"
      ></script>

      <script>
        var lastResponse;
        var copy;
        var stockOnDom;
        var lastItem;

        $(document).ready(function () {
          window.onscroll = () => {
            if (window.pageYOffset > 400) {
              $("#go-top").fadeIn();
            } else {
              $("#go-top").fadeOut();
            }
          };

          $("#go-top").click(function () {
            $([document.documentElement, document.body]).animate(
              {
                scrollTop: $("html").offset().top,
              },
              1000
            );
          });

          getMasks();

          setTimeout(function () {
            $(".item-container img").click(function () {
              copy = $(this).clone();

              $("#overlay-container").html(copy);

              $("#overlay-container div:first-child").removeClass(
                "item-container"
              );
              $("#overlay-container div:first-child").addClass("overlay-item");

              $(".overlay-item img:first-child").addClass("overlay-img");

              $(".overlay-item div:first-child").removeClass(
                "detail-container"
              );
              $(".overlay-item div:first-child").addClass("overlay-details");

              $("#overlay-container").show();
              $("#overlay-container").addClass("d-flex");
              $("body").addClass("overflow-hidden");
            });

            $("#overlay-container").click(function () {
              $("#overlay-container").hide();
              $("#overlay-container").removeClass("d-flex");
              $("#overlay-container").html("");
              $("body").removeClass("overflow-hidden");
            });
          }, 1000);
        });

        // Live updating like below not really necessary
        // setInterval(function () {
        //     getMasks();
        // }, 5000);

        function getMasks() {
          // console.log("getMasks() called")
          $.ajax({
            url: "get_masks.php",
            type: "get",
            dataType: "JSON",
            success: function (response) {
              if (_.isEqual(lastResponse, response)) {
                // console.log("DB Objects same, no update needed");
              } else {
                // console.log("DB Objects different");
                // console.log(response)
                let len = response.length;
                var i;
                for (i = 0; i < len; i++) {
                  let id = response[i].MaskId;
                  let fabricname = response[i].FabricName;
                  let imgurl = response[i].ImgUrl;
                  if (document.getElementById(fabricname) == null) {
                    // console.log("Item does not exist, creating new mask_item");
                    let mask_item = `
                      <div class="item-container" id="${fabricname}">
                          <img loading=lazy class="maskimg"  src="${imgurl}">
                          <div class="detail-container">

                              <h1> ${fabricname} </h1>

                              <?php
                                  if($_SESSION["user"] != "") {
                                      
                                  } else {
                                      echo "<a href='https://www.designsbytabitha.ca/session.html'><p>Login to order!</p></a>";
                                      echo "<div style='display: none;'>";
                                  }
                              ?>
                              
                              <button onclick="addToCart('${fabricname}')" style="margin-bottom: 10px; border: none; padding: 5px;">Add to cart</button>

                              <?php
                                  if($_SESSION["user"] != "") {
                                      
                                  } else {
                                      echo "</div>";
                                  }
                              ?>
                              
                          </div>
                      </div>
                  `;
                    $("#mask-item-container").append(mask_item);
                  }
                }
                lastResponse = response;
              }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
              alert("Down for Maintenance");
            },
          });
        } // END GETMASKS FUNCTION

        let cart;
        if (localStorage.getItem("cart") != null) {
          cart = JSON.parse(localStorage.getItem("cart"));
        } else {
          cart = [];
        }

        function addToCart(name) {
          // console.log(name)

          let size = "small";
          let qnty = 1;
          let lastItem;

          iziToast.destroy();

          iziToast.info({
            timeout: 0,
            overlay: true,
            zindex: 999,
            title: `${name}`,
            position: "center",
            drag: false,
            backgroundColor: "white",
            inputs: [
              [
                '<select><option value="small">Small</option><option value="medium">Medium</option><option value="large">Large</option></select>',
                "change",
                function (instance, toast, input, e) {
                  // console.log(input.value);
                  size = input.value;
                },
              ],
              [
                '<input type="number" value="1" style="width: 60px;">',
                "change",
                function (instance, toast, input, e) {
                  if (input.value <= 0) {
                    input.value = 1;
                  }
                  console.info(input.value);
                  qnty = input.value;
                },
              ],
            ],
            buttons: [
              [
                `<button>Add!</button>`,
                function (instance, toast) {
                  pushToCart(name, size, qnty);
                  iziToast.destroy();
                  iziToast.success({
                    position: "center",
                    title: "OK",
                    pauseOnHover: false,
                    message: "Added item to cart!",
                  });
                },
              ],
            ],
          });

          function pushToCart(itemName, itemSize, itemQnty) {
            let obj = [itemName, itemSize, parseInt(itemQnty)];

            let alreadyInCart = false;

            cart.forEach(function (item, index) {
              if (item[0] === itemName && item[1] === itemSize) {
                item[2] += parseInt(itemQnty);
                alreadyInCart = true;
              }
            });

            alreadyInCart
              ? console.log("Item existed in cart, updated qnty")
              : cart.push(obj);

            localStorage.setItem("cart", JSON.stringify(cart));
          }
        }

        let lastLoc;
        function viewCart() {
          iziToast.destroy();

          if (cart.length === 0) {
            iziToast.warning({
              title: "Cart Empty!",
              overlay: "true",
              position: "center",
              overlayClose: true,
            });
          } else {
            lastLoc = window.pageYOffset;
            window.scrollTo(0, 0);
            document.querySelector("body").classList.toggle("overflow-hidden");
            let cartDiv = document.getElementById("cartDiv");
            cartDiv.classList.toggle("d-none");
            updateCartDiv();
          }
        }

        function closeCart() {
          document.querySelector("body").classList.toggle("overflow-hidden");
          document.getElementById("cartDiv").classList.toggle("d-none");
          window.scrollTo(0, lastLoc);
        }

        function removeCartItem(arrayPos) {
          // console.log("Remove Trigger")
          cart.splice(arrayPos, 1);
          localStorage.setItem("cart", JSON.stringify(cart));
          updateCartDiv();
        }

        function updateCartDiv() {
          let cartItemDiv = document.getElementById("cartItemDiv");
          cartItemDiv.innerHTML = "";
          if (cart.length === 0) {
            cartItemDiv.insertAdjacentHTML(
              "beforeend",
              `
                    <div class="cartItem">
                        <p style="text-align: center;">Cart Empty!</p>
                    </div>
                    `
            );
          } else {
            cart.forEach(function (item, index) {
              cartItemDiv.insertAdjacentHTML(
                "beforeend",
                `
                            <div class="cartItem cartItem${index}">
                                <h3>${item[0]}</h3><button onclick="removeCartItem(${index})">Remove Item</button>
                                <h4>Size: ${item[1]}</h4>
                                <h4>Qnty: ${item[2]}</h4>
                            </div>
                        `
              );
            });
          }
        }

        async function postCart(cartArray) {
          if (cart.length === 0) {
            iziToast.warning({
              title: "Cart Empty!",
              overlay: "true",
              position: "center",
              overlayClose: true,
            });
          } else {
            iziToast.show({
              timeout: false,
              icon: "icon-person",
              title: "Send Order?",
              overlay: true,
              position: "center", // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
              progressBarColor: "rgb(0, 255, 184)",
              buttons: [
                [
                  "<button>Send</button>",
                  function (instance, toast) {
                    fetch("./post_create_order.php", {
                      method: "POST", // or 'PUT'
                      headers: {
                        "Content-Type": "application/json",
                      },
                      body: JSON.stringify(cartArray),
                    })
                      .then((response) => response.json())
                      .then((data) => {
                        // console.log('Success:', data);
                      })
                      .then((cart = []))
                      .then(localStorage.removeItem("cart"))
                      .then(updateCartDiv())
                      .catch((error) => {
                        console.error("Error:", error);
                      });

                    instance.destroy();

                    closeCart();

                    iziToast.success({
                      position: "center",
                      title: "Order Sent!",
                      overlayClose: true,
                      overlay: true,
                      messageColor: "Black",
                      message: "View order under Account -> My Orders",
                    });
                  },
                ],
                [
                  "<button>Cancel</button>",
                  function (instance, toast) {
                    iziToast.destroy();
                  },
                ],
              ],
            });
          }
        }
      </script>
    </div>
  </body>
</html>
