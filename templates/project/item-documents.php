<ul class="list primary">
    <li class="header">
        <i class="fa fa-file"></i> Documents
        <a href="project/<?php echo $project->id; ?>/documents/form-meta" target="#modal-card"
            class="pull-right" data-action="modal" data-target="#modal-form-meta">
            <i class="fa fa-plus"></i> Add
        </a>
    </li>
    <?php foreach ($project->documents as $document) : ?>
        <li>
            <a href="document/<?php echo $document->id; ?>">
                <h4><?php echo $document->title; ?></h4>
            </a>
            <strong>Translations</strong>
            <?php foreach ($project->languages as $language) :
                if ($language->id != $project->default_language_id) :
                    $url = "document/$document->id/translation/$language->id"; ?>
                <div style="margin-top: .75em">
                    <?php if (isset($document->translations[$language->id])) :
                        $translation = $document->translations[$language->id]; ?>
                        <a href="<?php echo $url; ?>"><?php echo $translation->title; ?></a>
                        <div class="row">
                            <div class="col-xs-6"><i class="fa fa-flag"></i>  <?php echo $language->name; ?></div>
                            <div class="col-xs-6"><i class="fa fa-user"></i> <?php echo $translation->user->alias; ?></div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo $url; ?>" class="btn sm text success">
                            <i class="fa fa-plus"></i> Add Translation
                        </a>
                        <div><i class="fa fa-flag"></i> <?php echo $language->name; ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; endforeach; ?>
        </li>
    <?php endforeach; ?>
    <li class="footer">
        <a href="project/<?php echo $project->id; ?>/documents">
            <em>See More</em>
        </a>
    </li>
</ul>
