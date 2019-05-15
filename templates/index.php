 <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="/index.php" method="get" autocomplete="off">
                    <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/index.php/?date=all" class="tasks-switch__item <?php if($date_select == "") : ?>tasks-switch__item--active<?php endif ?>">Все задачи</a>
                        <a href="/index.php/?<?=http_build_query(['date' => "today"])?>" class="tasks-switch__item <?php if($date_select == "today") : ?>tasks-switch__item--active<?php endif ?>">Повестка дня</a>
                        <a href="/index.php/?<?=http_build_query(['date' => "tomorrow"])?>" class="tasks-switch__item <?php if($date_select == "tomorrow") : ?>tasks-switch__item--active<?php endif ?>">Завтра</a>
                        <a href="/index.php/?<?=http_build_query(['date' => "overdue"])?>" class="tasks-switch__item <?php if($date_select == "overdue") : ?>tasks-switch__item--active<?php endif ?>">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
                        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if($show_complete_tasks == 1): ?> checked <?php endif ?> >
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

<table class="tasks"> 
<? foreach ($tasks as $key => $value) : ?> 
<? if ($show_complete_tasks == 1 or $value["status"] !== "1") : ?> 
    <tr class="tasks__item task <?php if(calculationDate($value["term"], $value["status"]) === true) : ?> task--important <?php endif?> <?php if($value["status"] === "1") : ?>task--completed <?php endif;?>"> 
    <td class="task__select"> 
        <label class="checkbox task__checkbox"> 
            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" name="checked" value="<?=$value['id_task']?>" <?php if($value["status"] === "1") : ?> checked <?php endif;?>> 
            <span class="checkbox__text"> <?=htmlspecialchars($value["name"]); ?></span> 
        </label> 
    </td> 
    <td class="task__file">
        <?php if(!empty($value['file'])) : ?><a class="download-link" href="<?=$value['file']?>"><?=$value['file']?> </a><?php endif?>
    </td>
    <td class="task__date"><?=correct_visual_date($value['term']); ?></td> 
    <td class="task__controls"></td> 
    </tr> 
<? endif; ?> 
<? endforeach; ?> 
</table>