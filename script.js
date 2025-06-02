
  // ==== Данные о людях ====
  const people = [
    {
      name: "Тамара Малюк",
      age: "85 лет",
      bio: "Пережила ужасы нацистской оккупации и рабства",
      photo: "people1.jpg",
      quotes: [
        "— Знаете, мои старшие братья и сестры удивлялись тому, как много я запомнила с тех лет — больше, чем они, — немного помолчав, 85-летняя Тамара Алексеевна Малюк добавляет: — Наверное, даже больше, чем мне бы хотелось..."
      ]
    },
    {
      name: "Раиса Корзун",
      age: "84 года",
      bio: "Бывшая узница концентрационного лагеря «Озаричи»",
      photo: "people2.jpg",
      quotes: [
        "– Родилась я в 1938 году. Мне было пять лет, когда меня с бабушкой и дедушкой, маминой младшей сестрой и ее шестимесячным ребенком вывезли в концлагерь «Озаричи». Очень много было населенных пунктов, которые мы прошли и откуда собирали людей. Детей, стариков, женщин… Лагерь «Озаричи» был обнесен колючей проволокой и кругом заминирован."
      ]
    },
    {
      name: "Мария Алексеевна Дубоделова",
      age: "84 года",
      bio: "Оказалась в Восточной Пруссии, в огороженном колючей проволокой в поле лагере, где было много бараков и откуда по утрам за ворота выводили взрослых на работу",
      photo: "people3.jpg",
      quotes: [
        "…Ей было суждено выжить уже хотя бы потому, что в оккупированном селе, где немецкий штаб был рядом с домом ее родителей, разъяренный фашист случайно не убил ее мать."
      ]
    }
  ];

  let currentPersonIndex = 0;

  function updatePersonInfo(index) {
    const person = people[index];
    document.getElementById("person-name").textContent = person.name;
    document.getElementById("person-age").innerHTML = `<strong>Возраст:</strong> ${person.age}`;
    document.getElementById("person-bio").innerHTML = `<strong>Биография:</strong> ${person.bio}`;
    document.getElementById("profile-pic").src = person.photo;
    document.getElementById("quote1").textContent = person.quotes[0];
    document.getElementById("moreLink").href = `index.php#person${index + 1}`;
  }

  // ==== Данные об образовательных статьях ====
  const articles = [
    {
      title: "Шталаг-352: людей давили танками, применяли извращенные казни",
      content: "С июля 1941-го по июнь 1944 г. Лагерь военнопленных, находившийся на территории современного микрорайона Масюковщина в Минске. Мученическую смерть здесь приняли более 80 тысяч человек.  Людей содержали в сараях с земляным полом, в жуткой вони и грязи, темноте. Хлипкие нары под телами людей ломались, и часто они оказывались придушенными или раздавленными. С особым цинизмом относились к проштрафившимся: на несколько дней их садили в миниатюрные клетки с холодным полом и колючей проволокой вместо крыши, встать в полный рост было невозможно. Мало кто это выдерживал. Бушевали болезни, в частности дизентерия. Изможденные люди при этом тяжело трудились. Умирали сотнями ежедневно. Кто своей смертью, а кого пристреливали, когда бросался к ограждению за гнилой картошкой. Посудой в лагере, в которую наливали несъедобную жижу, иногда служили шапки и даже ладони.  Применялись порка, повешение, мучительные казни. Например, подвешивали на крюки за подбородок. По сведениям местных жителей, места захоронения расстрелянных укатывали танком, но даже после этого земля продолжала шевелиться. ",
      image: "https://www.sb.by/upload/iblock/bba/bbaa8bdd9761ab42cb727d053ffd2ba1.jpg"
    },
    {
      title: "Холокост — трагедия еврейского народа",
      content: "Во время Второй мировой войны нацистская Германия организовала систематическое уничтожение еврейского населения Европы. Этот ужасный период истории вошёл в историю под названием Холокост. С 1941 по 1945 год около шести миллионов евреев были убиты в гетто, концентрационных лагерях и на расстрельных полигонах. Жестокость Холокоста выражалась не только в масштабах, но и в методах — газовые камеры, медицинские эксперименты, массовые депортации. Помимо евреев, жертвами стали цыгане, инвалиды, славяне, представители ЛГБТ-сообщества и другие группы. Холокост стал символом крайнего проявления ненависти и расизма.",
      image: "https://avatars.mds.yandex.net/i?id=7eb2a025e888968a41c3247ba044becf6b9f6b27-7909006-images-thumbs&n=13"
    },
    {
      title: "Почему важно помнить о геноцидах",
      content: "Геноцид — это не только трагедия прошлого, но и предупреждение на будущее. Знание истории массовых уничтожений, таких как Холокост, геноцид армян, события в Камбодже и Дарфуре, формирует у общества иммунитет против ненависти и насилия. Память о геноциде помогает обществу распознавать ранние признаки нетерпимости: расизм, национализм, пропаганду вражды. Она призывает к уважению прав человека и к защите уязвимых групп. Забвение — почва для повторения. Поэтому важно говорить, изучать и напоминать: „Никогда снова“ — это не лозунг, а призыв к действию.",
      image: "https://i.ytimg.com/vi/oJ87WehXwv0/maxresdefault.jpg"
    }
  ];

  let currentArticleIndex = 0;

  function showArticle(index) {
    const article = articles[index];
    document.getElementById('articleTitle').textContent = article.title;
    document.getElementById('articleContent').textContent = article.content;
    document.getElementById('articleImage').src = article.image;
  }

  function nextArticle() {
    currentArticleIndex = (currentArticleIndex + 1) % articles.length;
    showArticle(currentArticleIndex);
  }

  function prevArticle() {
    currentArticleIndex = (currentArticleIndex - 1 + articles.length) % articles.length;
    showArticle(currentArticleIndex);
  }

  // ==== Поиск и сортировка словаря ====
  function searchDictionary() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const list = document.getElementById("dictionaryList");
    const items = list.getElementsByTagName("li");

    for (let i = 0; i < items.length; i++) {
      const term = items[i].textContent || items[i].innerText;
      items[i].style.display = term.toLowerCase().includes(filter) ? "" : "none";
    }
  }

  function sortDictionary() {
    const list = document.getElementById("dictionaryList");
    const items = Array.from(list.getElementsByTagName("li"));
    items.sort((a, b) => (a.textContent || a.innerText).localeCompare(b.textContent || b.innerText));
    list.innerHTML = "";
    items.forEach(item => list.appendChild(item));
  }

  function toggleAdditionalTerms() {
    const extraTerms = document.querySelectorAll('.extra-term');
    const showMoreBtn = document.getElementById('showMoreBtn');

    extraTerms.forEach(term => {
      term.style.display = (term.style.display === 'none' || term.style.display === '') ? 'list-item' : 'none';
    });

    showMoreBtn.innerHTML = (showMoreBtn.innerHTML === 'Показать больше') ? 'Скрыть' : 'Показать больше';
  }

  // ==== Инициализация после загрузки страницы ====
  window.addEventListener('DOMContentLoaded', () => {
    updatePersonInfo(currentPersonIndex);
    showArticle(currentArticleIndex);
  });

  // ==== Навешиваем обработчики переключения людей ====
  document.getElementById("nextBtn").addEventListener("click", () => {
    currentPersonIndex = (currentPersonIndex + 1) % people.length;
    updatePersonInfo(currentPersonIndex);
  });

  document.getElementById("prevBtn").addEventListener("click", () => {
    currentPersonIndex = (currentPersonIndex - 1 + people.length) % people.length;
    updatePersonInfo(currentPersonIndex);
  });

function toggleAccessibility() {
    document.body.classList.toggle('accessibility');
    const btn = document.getElementById('accessibilityBtn');
    if (document.body.classList.contains('accessibility')) {
        btn.textContent = 'Обычная версия';
    } else {
        btn.textContent = 'Версия для слабовидящих';
    }
}


