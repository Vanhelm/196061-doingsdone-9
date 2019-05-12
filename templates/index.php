 <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="post" autocomplete="off">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                        <a href="/" class="tasks-switch__item">Повестка дня</a>
                        <a href="/" class="tasks-switch__item">Завтра</a>
                        <a href="/" class="tasks-switch__item">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
                        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if($show_complete_tasks == 1): ?> checked <?php endif ?> >
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

<table class="tasks"> 
<? foreach ($tasks as $key => $value) : ?> 
<? if ($show_complete_tasks === 1 or $value["status"] !== "1") : ?> 
    <tr class="tasks__item task <?php if(calculationDate($value["term"], $value["status"]) === true) : ?> task--important <?php endif?> <?php if($value["status"] === "1") : ?>task--completed <?php endif;?>"> 
    <td class="task__select"> 
        <label class="checkbox task__checkbox"> 
            <input class="checkbox__input visually-hidden" type="checkbox" name="checked" value="<?$value['task_id']?>"q <?php if($value["status"] === "1") : ?> checked <?php endif;?>> 
            <span class="checkbox__text"> <?=htmlspecialchars($value["name"]); ?></span> 
        </label> 
    </td> 
    <td class="task__file">
        <a class="download-link" href="<?=$value['file']?>">Home.psd</a>
    </td>
    <td class="task__date"><?=correct_visual_date($value['term']); ?></td> 
    <td class="task__controls"></td> 
    </tr> 
<? endif; ?> 
<? endforeach; ?> 
</table>