<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form" action="/add.php" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>
        <input class="form__input<?php if (!empty($errors['name'])) : ?> form__input--error <?php endif ?>" type="text"
               name="name" id="name" value="<?= $data['name'] ?? "" ?>" placeholder="Введите название">
        <?php if (!empty($errors['name'])) : ?> <p class="form__message"><?= $errors['name'] ?></p> <?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>
        <select class="form__input form__input--select<?php if (!empty($errors['project'])) : ?> form__input--error<?php endif ?>"
                name="project" id="project">
            <?php foreach ($projects as $key => $value): ?>
                <option<?php if ($id === intval($value['project_id'])) : ?> selected <?php endif ?>
                        value="<?= $value['project_id'] ?>"> <?= $value['project_name'] ?></option>
            <?php endforeach ?>
        </select>
        <?php if (!empty($errors['project'])) : ?> <p class="form__message"><?= $errors['project'] ?></p> <?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>
        <input class="form__input form__input--date<?php if (!empty($errors['date'])) : ?> form__input--error <?php endif ?>"
               type="text" name="date" id="date" value="<?= $_POST['date'] ?? "" ?>"
               placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        <?php if (!empty($errors['date'])) : ?> <p class="form__message"><?= $errors['date'] ?></p> <?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="file">Файл</label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="file" id="file" value="">
            <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
            </label>
            <?php if (!empty($errors['file'])) : ?><p class="form__message"><?= $errors['file'] ?></p> <?php endif ?>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
     