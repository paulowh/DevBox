function openCardModal(cardElement = null) {
    const cardId = cardElement.getAttribute("data-card-id");
    // const title = cardElement.querySelector(".card-title").textContent;
    if (!parseInt(cardId)) {
        fetch(`/cards/${cardId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Erro na requisição: " + response.status);
                }
                return response.json();
            })
    }

    // Abrir modal
    //   $("#card-modal").modal("show");
    document.getElementById("card-modal").style.display = "flex";
    carregarDropdownModal();
}

function carregarDropdownModal() {
    fetch("/cards/show")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Erro na requisição: " + response.status);
            }
            return response.json();
        })
        .then((data) => {
            Object.keys(data).forEach((key) => {
                // Transformando os objetos em formato {name, value}
                const values = data[key].map((item) => ({
                    name:
                        item.nome_curso ||
                        item.nome ||
                        item.sigla + "- " + item.nome_completo,
                    value: item.id,
                }));

                $(".ui.dropdown." + key).dropdown({
                    values: values,
                });
            });
        })
        .catch((error) => {
            console.error("Ocorreu um erro:", error);
        });
}

closeCardModal = function () {
    const modal = document.getElementById("card-modal");
    if (modal) {
        modal.style.display = "none";
    }
};
