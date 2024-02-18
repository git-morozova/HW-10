<?php
  header('Content-type: text/plain'); /////чтобы заработал перенос на новую строку при помощи "\n"
  $example_persons_array = [
      [
          'fullname' => 'Иванов Иван Иванович',
          'job' => 'tester',
      ],
      [
          'fullname' => 'Степанова Наталья Степановна',
          'job' => 'frontend-developer',
      ],
      [
          'fullname' => 'Пащенко Владимир Александрович',
          'job' => 'analyst',
      ],
      [
          'fullname' => 'Громов Александр Иванович',
          'job' => 'fullstack-developer',
      ],
      [
          'fullname' => 'Славин Семён Сергеевич',
          'job' => 'analyst',
      ],
      [
          'fullname' => 'Цой Владимир Антонович',
          'job' => 'frontend-developer',
      ],
      [
          'fullname' => 'Быстрая Юлия Сергеевна',
          'job' => 'PR-manager',
      ],
      [
          'fullname' => 'Шматко Антонина Сергеевна',
          'job' => 'HR-manager',
      ],
      [
          'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
          'job' => 'analyst',
      ],
      [
          'fullname' => 'Бардо Жаклин Фёдоровна',
          'job' => 'android-developer',
      ],
      [
          'fullname' => 'Шварцнегер Арнольд Густавович',
          'job' => 'babysitter',
      ],
  ];



///// РАЗБИЕНИЕ И ОБЪЕДИНЕНИЕ ФИО

//getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество.
//Возвращает как результат их же, но склеенные через пробел.
function getFullnameFromParts($surname,$name,$patronomyc){
  return ($surname . ' ' . $name . ' ' . $patronomyc);
}
/////echo getFullnameFromParts('Бардо','Жаклин','Фёдоровна');

//getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО. 
//Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’.
function getPartsFromFullname($full_name){  
  $keysArray = ['surname','name','patronomyc'];
  $valuesArray = explode(' ', $full_name);
  $partsFromFullname = array_combine($keysArray,$valuesArray);
  return ($partsFromFullname);
}
/////print_r (getPartsFromFullname('Бардо Жаклин Фёдоровна'));



///// СОКРАЩЕНИЕ ФИО

//Разработайте функцию getShortName, принимающую как аргумент строку, содержащую ФИО вида «Иванов Иван Иванович» 
//и возвращающую строку вида «Иван И.», где сокращается фамилия и отбрасывается отчество. 
//Для разбиения строки на составляющие используйте функцию getPartsFromFullname.
function getShortName($full_name){
  $array = getPartsFromFullname($full_name);
  return ($array['name'] . ' ' . mb_substr($array['surname'], 0, 1) . '.');  
}
/////echo getShortName('Бардо Жаклин Фёдоровна');  



///// ФУНКЦИЯ ОПРЕДЕЛЕНИЯ ПОЛА ПО ФИО

//Разработайте функцию getGenderFromName, принимающую как аргумент строку, 
//содержащую ФИО (вида «Иванов Иван Иванович»). 
function getGenderFromName($full_name){
  $array = getPartsFromFullname($full_name);
  $finalGenderMark = 0;

  ///// Ж
  if (mb_substr($array['patronomyc'], (mb_strlen($array['patronomyc']) - 3), mb_strlen($array['patronomyc'])) == "вна") {
    $finalGenderMark -= 1;
  }
  if (mb_substr($array['name'], (mb_strlen($array['name']) - 1), mb_strlen($array['name'])) == "а") {
    $finalGenderMark -= 1;
  }
  if (mb_substr($array['surname'], (mb_strlen($array['surname']) - 2), mb_strlen($array['surname'])) == "ва") {
    $finalGenderMark -= 1;
  }

  /////М
  if (mb_substr($array['patronomyc'], (mb_strlen($array['patronomyc']) - 2), mb_strlen($array['patronomyc'])) == "ич") {
    $finalGenderMark += 1;
  }
  if (mb_substr($array['name'], (mb_strlen($array['name']) - 1), mb_strlen($array['name'])) == "й" || mb_substr($array['name'], (mb_strlen($array['name']) - 1), mb_strlen($array['name'])) == "н") {
    $finalGenderMark += 1;
  }
  if (mb_substr($array['surname'], (mb_strlen($array['surname']) - 1), mb_strlen($array['surname'])) == "в") {
    $finalGenderMark += 1;
  }

  /////результат
  return $finalGenderMark <=> 0;    
}
/////echo getGenderFromName('Громов Александр Иванович');



///// ОПРЕДЕЛЕНИЕ ПОЛОВОГО СОСТАВА АУДИТОРИИ

//Напишите функцию getGenderDescription для определения полового состава аудитории. 
//Как аргумент в функцию передается массив, схожий по структуре с массивом $example_persons_array.
//Используйте для решения функцию фильтрации элементов массива, функцию подсчета элементов массива, функцию getGenderFromName, округление.
function getGenderDescription($array){         
  $menArray = array_filter($array, function($person) {
    return (getGenderFromName($person['fullname']) == 1);
  });
  $womenArray = array_filter($array, function($person) {
    return (getGenderFromName($person['fullname']) == -1);
  });
  $unknownArray = array_filter($array, function($person) {
    return (getGenderFromName($person['fullname']) == 0);
  });
  
  $menCount = round(count($menArray)*100/count($array), 1);
  $womenCount = round(count($womenArray)*100/count($array), 1);
  $unknownCount = round(count($unknownArray)*100/count($array), 1);

$genderDescription = 
"Гендерный состав аудитории:\n
---------------------------\n
Мужчины - $menCount% \n
Женщины - $womenCount%\n
Не удалось определить - $unknownCount%";
  return $genderDescription;  
}
/////echo getGenderDescription($example_persons_array);



///// ИДЕАЛЬНЫЙ ПОДБОР ПАРЫ

//Напишите функцию getPerfectPartner для определения «идеальной» пары.
//Как первые три аргумента в функцию передаются строки с фамилией, именем и отчеством (именно в этом порядке). 
//При этом регистр может быть любым: ИВАНОВ ИВАН ИВАНОВИЧ, ИваНов Иван иванович.
//Как четвертый аргумент в функцию передается массив, схожий по структуре с массивом $example_persons_array.
//Процент совместимости «Идеально на ...» — случайное число от 50% до 100% с точностью два знака после запятой.
function getPerfectPartner($surname, $name, $patronomyc, $array){ 
  $surname = mb_strtoupper(mb_substr($surname, 0,1)) . mb_strtolower(mb_substr($surname, 1));
  $name = mb_strtoupper(mb_substr($name, 0,1)) . mb_strtolower(mb_substr($name, 1));
  $patronomyc = mb_strtoupper(mb_substr($patronomyc, 0,1)) . mb_strtolower(mb_substr($patronomyc, 1));

  $currentUserFullname = getFullnameFromParts($surname, $name, $patronomyc);
  $currentUserShortName = getShortName($currentUserFullname);

  $currentUserGender = getGenderFromName($currentUserFullname);

  if ($currentUserGender == 0) {
    echo ("К сожалению, мы не смогли подобрать Вам идеальную пару");
  } else {                          
        do {
          $perfectPartnerIndex = rand(0, count($array)-1);
          $perfectPartnerFullname = $array[$perfectPartnerIndex]['fullname'];
          $perfectPartnerShortName = getShortName($perfectPartnerFullname);
          $perfectPartnerGender = getGenderFromName($perfectPartnerFullname);   
        }
        while ($perfectPartnerGender == $currentUserGender || $perfectPartnerGender == 0);

        $matchPercentage = rand(5000, 10000)/100;

$perfectPartnerResult = 
<<<MYHEREDOCTEXT
$currentUserShortName + $perfectPartnerShortName =
♡ Идеально на $matchPercentage% ♡
MYHEREDOCTEXT;

        return $perfectPartnerResult;                       
  }
}
/////echo getPerfectPartner('ИваНов', 'иван', 'ИВАНОВИЧ', $example_persons_array);
/////echo getPerfectPartner('', '', '', $example_persons_array);
/////echo getPerfectPartner('Красавина', 'Светлана', 'Кузьминична', $example_persons_array);