function openCardModal(cardElement = null) {
  const cardId = cardElement ? cardElement.getAttribute("data-card-id") : null;

  if (cardId && Number.isInteger(Number(cardId))) {
    fetch(`/cards/details/${cardId}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erro na requisição: " + response.status);
        }
        return response.text();
      })
      .then((html) => {
        document.getElementById("card-modal").innerHTML = html;
        document.getElementById("card-modal").style.display = "flex";
        $(".ui.dropdown").dropdown();
        carregarDropdownModal(cardId);

        // Adiciona o listener para futuras mudanças na UC dentro do modal
        $('#card-modal').on('change', '#card-field-uc', handleUcChange);
      })
      .catch((error) => {
        console.error('Erro ao carregar detalhes do card:', error);
      });
  } else {
    // Lógica para criar um novo card (se necessário)
    console.log("Abrindo modal para criar um novo card (ID não fornecido).");
  }
}

function carregarDropdownModal(id_valor = null) {
  const fetchUrl = id_valor ? `/cards/show/${id_valor}` : '/cards/show/';

  fetch(fetchUrl)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erro na requisição: " + response.status);
      }
      return response.json();
    })
    .then((data) => {
      Object.keys(data.drop).forEach((key) => {
        const values = data.drop[key].map((item) => {
          const isSelected = data.card ? (key === 'drop-ucs' && data.card.uc_id === item.id) || (key === 'drop-cursos' && data.card.curso_id === item.id) || (key === 'drop-turmas' && data.card.turma_id === item.id) : false;

          return {
            name: item.nome_curso || item.nome || `${item.sigla} - ${item.nome_completo}`,
            value: item.id,
            selected: isSelected,
          };
        });

        $(".ui.dropdown." + key).dropdown({
          values: values,
        });
      });

      if (data.card && data.card.uc_id) {
        // deixar timeout para garantir q rode depois!
        setTimeout(handleUcChange, 100);
      }
    })
    .catch((error) => {
      console.error("Ocorreu um erro:", error);
    });
}

function closeCardModal() {
  const modal = document.getElementById("card-modal");
  if (modal) {
    modal.style.display = "none";
    // Remove o listener para evitar duplicatas ao reabrir o modal
    $('#card-modal').off('change', '#card-field-uc');
  }
}

function populateMultiSelect(items) {
  Object.keys(items).forEach((key) => {
    const values = items[key].map((item) => ({
      name: item.name, value: item.value,
    }));

    $(".ui.dropdown." + key).dropdown({
      values: values,
    });
  });
}

async function handleUcChange() {
  const ucSelect = document.getElementById("card-field-uc");
  if (!ucSelect) return;

  const ucId = ucSelect.value;
  if (!ucId) return;

  try {
    const resp = await fetch(`/uc/${encodeURIComponent(ucId)}/related`, {
      headers: {"Accept": "application/json"}
    });

    if (!resp.ok) throw new Error("Erro ao carregar os campos relacionados à UC");
    const data = await resp.json();

    populateMultiSelect(data);
  } catch (err) {
    console.error(err);
    // limpa os dropdowns para evitar dados errados!
    populateMultiSelect([]);
  }
}
