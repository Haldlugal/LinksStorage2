
    let editPopup = document.getElementsByClassName("edit-popup")[0];
    let linkElements = document.getElementsByClassName("link-element");
    for (let i=0; i < linkElements.length; i++) {
        let editButton = linkElements[i].getElementsByClassName("btn-edit")[0];
        if (editButton) {
            editButton.addEventListener("click", function () {
                if (linkElements[i].getElementsByClassName("edit-popup")[0]) {
                    togglePopupVisibility(linkElements[i].getElementsByClassName("edit-popup")[0]);
                }
            });
            let closeButton = linkElements[i].getElementsByClassName("close-button")[0];
            closeButton.addEventListener("click", function () {
                hide(linkElements[i].getElementsByClassName("edit-popup")[0]);
            });
        }
    }

    let linkForms = document.getElementsByClassName("editLink-form");
    for (let i=0; i < linkForms.length; i++) {
        linkForms[i].addEventListener("submit", function(event){
            event.preventDefault();
            editLink(linkForms[i]);
        });
    }

    function togglePopupVisibility(popup) {
        if (document.getElementsByClassName("visible")[0] && document.getElementsByClassName("visible")[0] !== popup) {
            let openedPopup = document.getElementsByClassName("visible")[0];
            openedPopup.classList.remove("visible");
        }
        if (popup.classList.contains("visible")) {
            popup.classList.remove("visible");
        }
        else {
            popup.classList.add("visible");
        }
    }

    function hide(popup) {
        popup.classList.remove("visible");
    }

    function editLink(form) {
        let request = new XMLHttpRequest();
        let formData = new FormData(form);
        let linkId = form.getElementsByClassName("linkId")[0].value;
        request.open("POST", "link/edit/"+linkId);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let result = JSON.parse(this.response);
                let message = form.getElementsByClassName("result")[0];
                if (result.edited==="ok") {
                    let linkTitle = form.getElementsByClassName("linkTitle")[0].value;
                    let linkUrl = form.getElementsByClassName("linkUrl")[0].value;
                    let linkPrivacy = form.getElementsByClassName("linkPrivacy")[0].checked;
                    let link = document.getElementById("link-" + linkId);
                    form.getElementsByClassName("pastLinkUrl")[0].value = linkUrl;
                    link.getElementsByClassName("linkTitle")[0].innerHTML = linkTitle;
                    link.getElementsByClassName("linkUrl")[0].innerHTML = linkUrl;
                    if(linkPrivacy) {
                        link.getElementsByClassName("linkPrivate")[0].classList.remove("d-none");
                        link.getElementsByClassName("linkPrivate")[0].classList.add("d-inline");
                    }
                    else {
                        link.getElementsByClassName("linkPrivate")[0].classList.remove("d-inline");
                        link.getElementsByClassName("linkPrivate")[0].classList.add("d-none");
                    }
                }
                message.innerText = result.message;
            }
        };
        request.send(formData);
    }




/*<script>
    function showEditPopup(link) {
        let editPopup = document.getElementById("edit-popup");
        let popupBackground = document.getElementById("popup-background");
        let message = document.getElementById("result");
        message.innerText = "";
        popupBackground.style.display = "block";
        editPopup.classList.remove("d-none");
        editPopup.classList.add("d-flex");
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let result = JSON.parse(this.response);
                document.getElementById("linkId").value = result.id;
                document.getElementById("pastLinkUrl").value = result.url;
                document.getElementById("userId").value = result.userId;
                document.getElementById("linkTitle").value = result.title;
                document.getElementById("linkUrl").value = result.url;
                document.getElementById("linkDescription").value = result.description;
                if (result.private === 1) {
                    document.getElementById("linkPrivacy").checked = true;
                }
                else {
                    document.getElementById("linkPrivacy").checked = false;
                }
            }
        };
        xhttp.open("GET", "link/read/"+link, true);
        xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhttp.send();
    }

    function hideEditPopup() {
        let editPopup = document.getElementById("edit-popup");
        let popupBackground = document.getElementById("popup-background");
        editPopup.classList.remove("d-flex");
        editPopup.classList.add("d-none");
        popupBackground.style.display = "none";
    }

    function editLink() {
        let form = document.getElementById("edit-form");
        let request = new XMLHttpRequest();
        let formData = new FormData(form);
        let linkId = document.getElementById("linkId").value;
        request.open("POST", "link/edit/"+linkId);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                console.log(this.response);
                let result = JSON.parse(this.response);
                let message = document.getElementById("result");
                if (result.edited==="ok") {
                    let linkTitle = document.getElementById("linkTitle").value;
                    let linkUrl = document.getElementById("linkUrl").value;
                    let linkPrivacy = document.getElementById("linkPrivacy").checked;
                    let link = document.getElementById("link-" + linkId);
                    link.getElementsByClassName("linkTitle")[0].innerHTML = linkTitle;
                    link.getElementsByClassName("linkUrl")[0].innerHTML = linkUrl;
                    if(linkPrivacy) {
                        link.getElementsByClassName("linkPrivate")[0].classList.remove("d-none");
                        link.getElementsByClassName("linkPrivate")[0].classList.add("d-inline");
                    }
                    else {
                        link.getElementsByClassName("linkPrivate")[0].classList.remove("d-inline");
                        link.getElementsByClassName("linkPrivate")[0].classList.add("d-none");
                    }
                    hideEditPopup();
                }
                message.innerText = result.message;
            }
        };
        request.send(formData);
    }
</script>
*/