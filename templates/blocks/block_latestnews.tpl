<{if $block.latestnews_scroll }>
<marquee behavior="scroll" class="center" direction="up" height="<{$block.scrollheight}>" scrollamount="3"
         scrolldelay="<{$block.scrollspeed}>" onmouseover="this.stop()" onmouseout="this.start()">
<{/if}>

    <table class="width100 bnone">
        <tr>
            <{section name=i loop=$block.columns}>
                <td width="<{$block.spec.columnwidth}>%">
                    <{foreach item=news from=$block.columns[i]}>
                        <div class="item">
                            <div class="itemHead"><span class="itemTitle"><{$news.topic_title}><{$news.title}></span>
                                <{$news.admin}>
                            </div>
                            <div class="itemInfo">
                                <span class="itemPoster"><{$news.poster}></span>
                                <span class="itemPostDate"><{$news.posttime}><{$news.read}></span>
                            </div>
                            <div class="latestnews">
                                <div class="itemText"><{$news.text}></div>
                                <div class="clear"></div>
                            </div>
                            <div class="itemFoot">
                                <span class="itemPermaLink"><{$news.more}><{$news.comment}><{$news.print}><{$news.pdf}><{$news.email}></span>
                            </div>
                        </div>
                    <{/foreach}>
                </td>
            <{/section}>
        </tr>
    </table>

    <{if $block.latestnews_scroll }>
</marquee>
<{/if}>

<div class="right"><{$block.morelink}><{$block.topiclink}><{$block.archivelink}><{$block.submitlink}></div>
