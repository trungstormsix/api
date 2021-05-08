<?php
error_reporting(~E_ALL);
$helper = new \App\library\OcoderHelper();
echo '<?xml version="1.0" encoding="UTF-8"?>'
?>
<lists>
    <?php
    if ($result) {
        foreach ($result as $question) {
            ?>
            <Table>
                <id><?php echo $question->id; ?></id>
                <question><?php echo htmlentities($question->question); ?></question>
                <answers>
                    <?php
                    $answers = json_decode($question->answers);
                    shuffle($answers);
                    foreach ($answers as $answer) {
                        ?>
                    <answer><?php echo $answer ?></answer>
<?php } ?>

                </answers>
                <explanation><?php echo $helper->encrypt($question->explanation) ?></explanation>
                <correct><?php echo $helper->encrypt($question->correct) ?></correct>
                <articles>                    
                    <?php
                    $articles = $question->article;
                    foreach ($articles as $article) {
                        ?>
                    <article id="<?php echo $article->id ?>"><?php echo htmlentities($article->title) ?></article>
        <?php } ?>

                </articles>
            </Table>
            <?php
        };
    } else {
        ?>
        <Table>
            <id></id>
            <question>We are sorry, there is no question found. :(</question>
            <answers>

            </answers>
            <explanation></explanation>
            <correct></correct>
        </Table>
<?php } ?>
</lists>