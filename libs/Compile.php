<?php

namespace libs;

class Compile
{
    private $template; // 待编译的文件
    private $content; // 需要替换的文件
    private $comfile; // 编译后的文件
    private $left = '{'; // 左定界符
    private $right = '}'; // 右定界符
    private $value = array(); // 值栈
    private $phpTurn;
    private $T_P = array(); // 匹配正则数组
    private $T_R = array(); // 替换数组
    public function __construct($template, $compileFile, $config)
    {
        $this->template = $template;
        $this->comfile = $compileFile;
        $this->content = file_get_contents($template);
        if ($config['php_turn'] === true) {
            $this->T_P[] = "#/<\?(=|php|)(.+?)\?>/is#";
			$this->T_R[] = "&lt;? \\1\\2? &gt";
        }
        // 变量匹配
        // \x7f-\xff表示ASCII字符从127到255，其中\为转义，作用是匹配汉字
        $this->T_P[] = "#\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}#";
        // foreach标签盘匹配
        $this->T_P[] = "#\{(loop|foreach)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}#i";
        $this->T_P[] = "#\{\/(loop|foreach|if)\}#";
        $this->T_P[] = "#\{([k|v])\}#";
        // if else标签匹配
        $this->T_P[] = "#\{if (.*?)\}#";
        $this->T_P[] = "#\{(else if|elseif) (.*?)\}#";
        $this->T_P[] = "#\{else\}#";
        $this->T_P[] = "#\{(\#|\*)(.*?)(\#|\*)\}#";

        $this->T_R[] = "<?php echo \$this->value['\\1']; ?>";
        $this->T_R[] = "<?php foreach ((array)\$this->value['\\2'] as \$k => \$v) { ?>";
        $this->T_R[] = "<?php } ?>";
        $this->T_R[] = "<?php echo \$\\1?>";
        $this->T_R[] = "<?php if(\\1){ ?>";
        $this->T_R[] = "<?php }elseif(\\2){ ?>";
        $this->T_R[] = "<?php }else{ ?>";
        $this->T_R[] = "";
    }
    public function compile()
    {
        $this->c_var();
        //$this->c_staticFile();
        file_put_contents($this->comfile, $this->content);
    }
    public function c_var()
    {
        $this->content = preg_replace($this->T_P, $this->T_R, $this->content);
    }
    /* 对引入的静态文件进行解析，应对浏览器缓存 */
    public function c_staticFile()
    {
        $this->content = preg_replace('#\{\!(.*?)\!\}#', '<script src=\1' . '?t=' . time() . '></script>', $this->content);
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
}
