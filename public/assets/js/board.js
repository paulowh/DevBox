function openCardModal(cardElement = null) {
  const cardId = cardElement ? cardElement.getAttribute("data-card-id") : null;

  if (cardId && Number.isInteger(Number(cardId))) {
    fetch(`/cards/details/${cardId}`)
      .then(function (response) {
        if (!response.ok) {
          throw new Error("Erro na requisição: " + response.status);
        }
        return response.text();
      })
      .then(function (html) {
        document.getElementById("card-modal").innerHTML = html;
        document.getElementById("card-modal").style.display = "flex";
        $(".ui.dropdown").dropdown();
        carregarDropdownModal(cardId);

        $('#card-modal').on('change', '#card-field-uc', handleUcChange);
      })
      .catch(function (error) {
        console.error('Erro ao carregar detalhes do card:', error);
      });
  } else {
    console.log("Abrindo modal para criar um novo card (ID não fornecido).");
  }
}

function carregarDropdownModal(id_valor) {
  const fetchUrl = id_valor ? `/cards/show/${id_valor}` : '/cards/show/';

  fetch(fetchUrl)
    .then(function (response) {
      if (!response.ok) {
        throw new Error("Erro na requisição: " + response.status);
      }
      return response.json();
    })
    .then(function (data) {
      Object.keys(data.drop).forEach(function (key) {
        const values = data.drop[key].map(function (item) {
          const isSelected = data.card ? (key === 'drop-ucs' && data.card.uc_id === item.id) || (key === 'drop-cursos' && data.card.curso_id === item.id) || (key === 'drop-turmas' && data.card.turma_id === item.id) : false;
          return {
            name: item.nome_curso || item.nome || (item.sigla + ' - ' + item.nome_completo),
            value: item.id,
            selected: isSelected,
          };
        });
        $(".ui.dropdown." + key).dropdown({values: values});
      });

      if (data.card && data.card.uc_id) {
        handleUcChange(data.card.uc_id, data.card);
      }
    })
    .catch(function (error) {
      console.error("Ocorreu um erro:", error);
    });
}

function closeCardModal() {
  const modal = document.getElementById("card-modal");
  if (modal) {
    modal.style.display = "none";
    $('#card-modal').off('change', '#card-field-uc');
  }
}

function populateMultiSelect(items, cardData) {
  const relatedKeys = ['indicadores', 'conhecimentos', 'habilidades', 'atitudes'];

  relatedKeys.forEach(function (key) {
    const dropdown = $(".ui.dropdown." + key);
    if (dropdown.length === 0) return;

    const currentItems = (items && items[key]) ? items[key] : [];
    const values = currentItems.map(function (item) {
      return {name: item.name, value: item.value};
    });

    dropdown.dropdown({values: values});
    dropdown.dropdown('clear');

    if (cardData && cardData[key] && Array.isArray(cardData[key])) {
      const selectedIds = cardData[key].map(function (id) {
        return String(id);
      });
      if (selectedIds.length > 0) {
        dropdown.dropdown('set selected', selectedIds);
      }
    }
  });
}

function handleUcChange(ucIdOrEvent, cardData) {
  let ucId;
  let isChangeEvent = typeof ucIdOrEvent === 'object' && ucIdOrEvent !== null && ucIdOrEvent.target;

  if (isChangeEvent) {
    ucId = ucIdOrEvent.target.value;
    cardData = null; // Limpa cardData em uma mudança manual para não selecionar itens antigos
  } else {
    ucId = ucIdOrEvent;
  }

  if (!ucId) {
    populateMultiSelect({}, null); // Limpa todos os campos
    return;
  }

  fetch('/uc/' + encodeURIComponent(ucId) + '/related', {
    headers: {"Accept": "application/json"}
  })
    .then(function (resp) {
      if (resp.status === 404) { // Se a UC não tiver itens relacionados
        return {}; // Retorna um objeto vazio para limpar os campos
      }
      if (!resp.ok) {
        throw new Error('Erro na requisição: ' + resp.status);
      }
      return resp.json();
    })
    .then(function (relatedData) {
      populateMultiSelect(relatedData, cardData);
    })
    .catch(function (err) {
      console.error('Falha ao buscar ou processar dados da UC:', err);
      populateMultiSelect({}, null); // Limpa os campos em caso de erro
    });
}
