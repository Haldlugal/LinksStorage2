document.addEventListener('DOMContentLoaded', function() {
    const linkElements = document.getElementsByClassName("link-element");
    for (let key of linkElements){
        console.log(linkElements);
    }
    for (let i=0; i < linkElements.length; i++) {
        const editButton = linkElements[i].getElementsByClassName("btn-edit")[0];
        if (editButton) {
            editButton.addEventListener("click", function () {
                if (linkElements[i].getElementsByClassName("edit-popup")[0]) {
                    togglePopupVisibility(linkElements[i].getElementsByClassName("edit-popup")[0]);
                }
            });
            const closeButton = linkElements[i].getElementsByClassName("close-button")[0];
            closeButton.addEventListener("click", function () {
                hide(linkElements[i].getElementsByClassName("edit-popup")[0]);
            });
        }
    }

    const linkForms = document.getElementsByClassName("editLink-form");
    for (let i=0; i < linkForms.length; i++) {
        linkForms[i].addEventListener("submit", function(event){
            event.preventDefault();
            editLink(linkForms[i]);
        });
    }


    function togglePopupVisibility(popup) {
        popup.getElementsByClassName("result")[0].innerHTML = "";
        if (document.getElementsByClassName("visible")[0] && document.getElementsByClassName("visible")[0] !== popup) {
            const openedPopup = document.getElementsByClassName("visible")[0];
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
        const request = new XMLHttpRequest();
        const formData = new FormData(form);
        const linkId = form.getElementsByClassName("linkId")[0].value;
        request.open("POST", "link/edit/"+linkId);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                const result = JSON.parse(this.response);
                const message = form.getElementsByClassName("result")[0];
                if (result.edited==="ok") {
                    const linkTitle = form.getElementsByClassName("linkTitle")[0].value;
                    const linkUrl = form.getElementsByClassName("linkUrl")[0].value;
                    const linkPrivacy = form.getElementsByClassName("linkPrivacy")[0].checked;
                    const link = document.getElementById("link-" + linkId);
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

    $('#deleteModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const url = button.data('url');
        const deleteButton = $(".delete-btn");
        deleteButton.on('click', function(){
            location.replace(url);
        });
    });
});
