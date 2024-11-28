<div class="modal-container" id="myModal">
        <div class="modal-wrapper">
          <div class="modal">
            <header class="text-center">
              <h2>Add New Shop</h2>
            </header>
            <main>
              <div class="text-wrapper">
                <div id="formErrors"></div>
                <div id="formResult"></div>
                <form class="shop-form">
                    <label for="shop-name">Name of shop:</label>
                    <input type="text" id="shop-name" name="shop-name"><br><br>
                    <input type="hidden" name="shop-type" id="shop-category">
                    <input type="hidden" name="id" id="shop-id">
                    <div class="dropdown form-dropdown">
                        <a href="#" class="js-link">Type of shop: <i class="fa fa-chevron-down"></i></a>
                        <ul class="js-dropdown-list">
                            <li>Femme</li>
                            <li>Homme</li>
                            <li>Famille</li>
                            <li>Enfant</li>
                            <li>* Reset</li>
                        </ul>
                    </div>

                    <label for="shop-location">Shop Location:</label>
                    <input type="text" id="shop-location" name="shop-location"><br><br>

                    <label for="shop-timesheet">Shop Timesheet:</label>
                    <input type="text" id="shop-timesheet" name="shop-timesheet" required><br><br>

                </form>
              </div>
            </main>
            <footer>
              <div class="btn-container">
                <div class="cancel-wrapper">
                  <button class="btn btn-cancel">Cancel</button>
                </div>
                <div class="submit-confirm-wrapper">
                  <button class="btn btn-confirm">
                    <i class="fa-solid fa-check"></i> Sumbit
                  </button>
                </div>
              </div>
            </footer>
          </div>
        </div>
      </div>