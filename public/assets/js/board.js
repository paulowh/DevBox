let boardState = {
  isEditing: false,
  currentCard: null,
};

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
      boardState.currentCard = data.card || null;
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
  boardState.isEditing = false;
  boardState.currentCard = null;
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

toggleCardEditing = function (forceEdit = null) {
  boardState.isEditing = forceEdit !== null ? forceEdit : !boardState.isEditing;

  const viewMode = document.getElementById('card-view-mode');
  const editMode = document.getElementById('card-edit-mode');
  const toggleBtn = document.getElementById('card-edit-toggle-btn');
  const fieldSelects = document.querySelectorAll('.card-field-select, .card-field-input');
  const dropdowns = $('#card-modal .ui.dropdown');

  if (boardState.isEditing) {
    // Modo edição
    if (viewMode) viewMode.style.display = 'none';
    if (editMode) editMode.style.display = 'block';
    if (toggleBtn) toggleBtn.textContent = 'Cancelar';

    fieldSelects.forEach(field => field.disabled = false);
    dropdowns.removeClass('disabled');
  } else {
    // Modo visualização
    if (viewMode) viewMode.style.display = 'block';
    if (editMode) editMode.style.display = 'none';
    if (toggleBtn) toggleBtn.textContent = 'Editar';

    fieldSelects.forEach(field => field.disabled = true);
    dropdowns.addClass('disabled');

    // Se estava criando novo card e cancelou, fechar modal
    if (!boardState.currentCard?.id) {
      closeCardModal();
    }
  }
};

function handleCardUpdate() {
  const cardId = boardState.currentCard.id;
  if (!cardId) {
    console.error("ID do card não encontrado.");
    return;
  }

  const updatedData = {
    titulo: document.getElementById('card-edit-title').value,
    descricao: document.getElementById('card-edit-description').value,
    turma_id: document.getElementById('card-field-turma').value,
    curso_id: document.getElementById('card-field-curso').value,
    uc_id: document.getElementById('card-field-uc').value,
    aula_inicial: document.getElementById('card-field-aula-inicial').value,
    aula_final: document.getElementById('card-field-aula-final').value,
    indicadores: $('#card-field-indicadores-container').val(),
    conhecimentos: $('#card-field-conhecimentos-container').val(),
    habilidades: $('#card-field-habilidades-container').val(),
    atitudes: $('#card-field-atitudes-container').val(),
  };

  fetch(`/cards/update/${cardId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(updatedData),
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Erro ao atualizar o card');
      }
      return response.json();
    })
    .then(data => {
      console.log('Card atualizado com sucesso:', data);
      toggleCardEditing(false)
    })
    .catch(error => {
      console.error('Erro na requisição:', error);
    });
}
