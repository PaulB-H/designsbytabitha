<?php
  include("../php/session_start.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masks by Tabitha</title>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
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
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="../styles/mask_page.css" />

    <style>
      .item-container {
        display: inline-block;
        vertical-align: top;
        width: 100%;
      }


      .maskimg {
        display: inline-block;
        vertical-align: middle;
        width: 100%;
        height: auto; 
        min-height: 188px !important;
      }

      .loading {
        content: url("../images/icons/loader.gif");
      }
    </style>
  </head>

  <body>
    <div id="cartDiv" class="d-none">
      <div id="cartBtns">
        <button onclick="closeCart()" style="margin-bottom: 10px">
          Close Cart
        </button>
        <button onclick="postCart(cart)" style="margin-bottom: 10px">
          Send Cart
        </button>
        <div id="cartItemDiv"></div>
      </div>
    </div>

    <a id="back-btn" href="../home.html"> Back </a>

    <div id="account">
      <button
        id="accountBtn"
        onclick="window.location.href='session.php'"
      >
        Account
      </button>

      <?php

        if (empty($_SESSION) || empty($_SESSION["user"])) {
          echo "<div style='display: none;'>";
        } else {
            
        }
      ?>

      <button
        id="viewCartBtn"
        onclick="viewCart()"
      >
        View Cart
      </button>

      <?php
      if (empty($_SESSION) || empty($_SESSION["user"])) {
        echo "</div>"; 
      } else {
          
      }
      ?>
      
    </div>

    <h1 class="main-header" data-aos="fade-right">Face Masks</h1>
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

    <h3 style="text-align: center" data-aos="fade-right">Sizes</h3>
    <div class="size-table" data-aos="fade-right">
      <p>Average</p>
      <p>Large</p>
      <p class="no-wrap">Child (3-6yrs)</p>
      <p class="no-wrap">Youth (7-12yrs)</p>
    </div>

    <!-- <div class="made-2-order-msg">
      <p>All other sizes or out-of-stock items available made to order</p>
    </div> -->

    <div class="fade-in-container">
      <h1>LOADING...</h1>
      <img id="loadingSpinner" src="../images/icons/loader.gif" alt="Loading..." />
    </div>

    <!-- <div id="mask-item-container" class="">
      EACH MASK ITEM APPENDED IN HERE 
      <div id="overlay-container" class="overlay" style="display: none"></div>
    </div> -->

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
        <img class="Ko-fi-icon" src="../images/icons/Ko-fi_small.png" />
      </div>
    </a>

    <div
      class="footer-container"
      style="color: lightgray"
    >
      <!-- <div class="footer">
        &copy; Web by Paul
        <i
          class="fas fa-link"
          style="margin-left: 5px; margin-top: 2px; font-size: 90%"
        ></i>
      </div> -->
      <div class="footer">
        Preloader by <a href="https://icons8.com/" target="_blank" rel="noopener noreferrer">icons8</a>
      </div>
    </div>

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

      <!-- AOS -->
      <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

      <script>
        AOS.init();
        
        var lastResponse;
        var copy;
        var stockOnDom;
        var lastItem;
        var maskItemsLoaded = false;

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

          var attachScriptInterval = setInterval(attachScript, 500);

          function attachScript(){
            if (maskItemsLoaded === true){
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

              clearInterval(attachScriptInterval);

            }
          }

        }); // document.ready end

        // Check for updates interval
        // setInterval(function () {
        //     getMasks();
        // }, 5000);

        // function getMasks() {
        //   $.ajax({
        //     url: "../php/get_masks.php",
        //     type: "get",
        //     dataType: "JSON",
        //     success: function (response) {
        //       // _.isEqual() to compare arrays 
        //       // note underscore from lodash
        //       if (_.isEqual(lastResponse, response)) {
        //         console.log("DB Objects same, no update needed");
        //       } else {
        //         console.log("DB Objects different");
        //         let len = response.length;
        //         var i;
        //         for (i = 0; i < len; i++) {
        //           let id = response[i].MaskId;
        //           let fabricname = response[i].FabricName;
        //           let imgurl = response[i].ImgUrl;
        //           if (document.getElementById(fabricname) == null) {
        //             // console.log("Item does not exist, creating new mask_item");
        //             let mask_item = `
        //               <div class="item-container" id="${fabricname}">
        //                   <img loading=lazy class="maskimg"  src="../${imgurl}">
        //                   <div class="detail-container">

        //                     <h1> ${fabricname} </h1>

        //                     <?php

        //                       if (empty($_SESSION) || empty($_SESSION["user"])) {
        //                         echo "<a href='./session.php'><p>Login to order!</p></a>";
        //                         echo "<div style='display: none;'>";
        //                       } else {
                                  
        //                       }
                              
        //                     ?>
                            
        //                     <button onclick="addToCart('${fabricname}')" style="margin-bottom: 10px; border: none; padding: 5px;">Add to cart</button>

        //                     <?php
        //                     if (empty($_SESSION) || empty($_SESSION["user"])) {
        //                       echo "</div>";
        //                     } else {
                                
        //                     }
                              
        //                     ?>
                              
        //                   </div>
        //               </div>
        //           `;
        //             $("#mask-item-container").append(mask_item);
        //           }
        //         }
        //         maskItemsLoaded = true;
        //         lastResponse = response;
        //       }
        //     },
        //     error: function (XMLHttpRequest, textStatus, errorThrown) {
        //       // alert(`XMLHttpRequest err: ${errorThrown}`);
        //       console.error("XMLHttpRequest error:", XMLHttpRequest);
        //       console.error("Status:", textStatus);
        //       console.error("Error thrown:", errorThrown);
        //       alert("Error occurred. Check console for details.");
        //     },
        //   });
        // } // END GETMASKS FUNCTION

        function getMasks() {
  $.ajax({
    url: "../php/get_masks.php",
    type: "get",
    dataType: "JSON",
    success: function (response) {
      if (_.isEqual(lastResponse, response)) {
        console.log("DB Objects same, no update needed");
      } else {
        console.log("DB Objects different");
        let len = response.length;

        for (let i = 0; i < len; i++) {
          let id = response[i].MaskId;
          let fabricname = response[i].FabricName;
          let imgurl = response[i].ImgUrl;

          if (document.getElementById(fabricname) == null) {
            let mask_item = `
              <div class="item-container" id="${fabricname}">
                <img loading="" class="maskimg" data-src="../${imgurl}" src="../images/icons/loader.gif" alt="Loading..." />
                <div class="detail-container">
                  <h1>${fabricname}</h1>
                  <?php
                    if (empty($_SESSION) || empty($_SESSION["user"])) {
                      echo '<a href="./session.php"><p>Login to order!</p></a>';
                      echo '<div style="display: none;">';
                    }
                  ?>
                  <button onclick="addToCart('${fabricname}')" style="margin-bottom: 10px; border: none; padding: 5px;">Add to cart</button>
                  <?php
                    if (empty($_SESSION) || empty($_SESSION["user"])) {
                      echo '</div>';
                    }
                  ?>
                </div>
              </div>
            `;

            $("#mask-item-container").append(mask_item);
          }
        }
        maskItemsLoaded = true;
        lastResponse = response;
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      console.error("XMLHttpRequest error:", XMLHttpRequest);
      console.error("Status:", textStatus);
      console.error("Error thrown:", errorThrown);
      alert("Error occurred. Check console for details.");
    },
  });
}

        let cart;
        if (localStorage.getItem("cart") != null) {
          cart = JSON.parse(localStorage.getItem("cart"));
        } else {
          cart = [];
        }

        function addToCart(name) {
          // console.log(name)

          // Default size is small, unless another size is selected from input
          // Backend will only accept small, medium, or large
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
                    <h4>Qnty: ${item[2]}
                      <span 
                        class="qntyIncrease"
                        onclick="function increaseItem${index}(){
                          cart[${index}][2] ++;
                          updateCartDiv();
                          localStorage.setItem('cart', JSON.stringify(cart));
                        }; increaseItem${index}()"
                      > 
                        +1 
                      </span>

                      <span 
                        class="qntyDecrease"
                        onclick="function decreaseItem${index}(){
                          if(cart[${index}][2] > 1){
                            cart[${index}][2] --;
                            updateCartDiv();
                            localStorage.setItem('cart', JSON.stringify(cart));
                          }
                        }; decreaseItem${index}()"
                      > 
                        - 1
                      </span>
                    </h4>
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
                    fetch("../php/post_create_order.php", {
                      method: "POST",
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

        window.setTimeout(() => {
          let images = document.querySelectorAll(".maskimg");
          images.forEach(function (image) {
            // Replace the placeholder with the actual image source
            image.src = image.getAttribute("data-src");

            // Hide the loading spinner after the image is loaded
            image.classList.remove("loading");
              // });
          });
        }, 2000);

        document.addEventListener("DOMContentLoaded", function () {
          let images = document.querySelectorAll(".maskimg");

          images.forEach(function (image) {
            if (image.hasAttribute("data-src")) {
              // image.addEventListener("load", function () {
                console.log("Image loaded:", image.src);

                // Replace the placeholder with the actual image source
                image.src = image.getAttribute("data-src");

                // Hide the loading spinner after the image is loaded
                image.classList.remove("loading");
              // });
            }
          });
        });
      </script>
    </div>
  </body>
</html>
