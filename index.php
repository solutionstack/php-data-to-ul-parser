<?php
/**
 * Created by IntelliJ IDEA.
 * User: Olubodun Abalaya
 * Date: 3/16/2018
 * Time: 12:52 PM
 */

/**
 *  SYNOPSIS:-
 *
 *  The Program reads in formatted input from a text file
 *  And creates HTML unordered menus.
 *  The data in the text file is formatted based on a few rules
 *
 *      -Every new indentation level begins a new <ul> block
 *      -Indentation levels are in multiples of four (spaces) starting from the first line
 *      -Lines not conforming to the set indentation rules are ignored
 *
 *  Call file as
 *  `new TextToUL("data_fale_path")`;
 *
 *  Output is returned to STDOUT
 */

namespace solutionstack {

    class TextToUL
    {

        const MARKUP_BLOCK_START = "<ul>";
        const MARKUP_BLOCK_CHILD = "<li>";
        const MARKUP_BLOCK_CHILD_CLOSE = "</li>";
        const MARKUP_BLOCK_CLOSE = "</ul>";

        protected $current_indent_level = 0;
        protected $indent_mltiple = 4;
        protected $markup_text;
        protected $data_file_handle;

        public function __construct(string $data_file)
        {
            //open data file for reading
            $this->data_file_handle = (new \SplFileInfo($data_file))->openFile();

            while ( ! $this->data_file_handle->eof()) {

                //send each line over to build the list
                $this->build_menu($this->data_file_handle->fgets()) . "<br/>";
            }

            echo($this->markup_text); //output and end
        }

        protected function build_menu(string $line)
        {
            if ( ! strlen($line)) {//empty line, ignore
                return;
            }


            if ($this->current_indent_level === 0) {//CASE 1: initial indent is 0, so we at start of file

                $this->markup_text .= self::MARKUP_BLOCK_START; //start first <ul>

                //add first <li> but dont close as we might have an inner <ul>
                $this->markup_text .= self::MARKUP_BLOCK_CHILD . trim($line);

                $this->current_indent_level = 4; //cache
                return;

            }
            else {

                $ascii_space = 32;
                $space_count = 0;
                $i = 0;

                while (ord($line[$i++]) === $ascii_space) {//count the space count before the first non-space char
                    ++$space_count;
                }

                if ($space_count % 4) {
                    // $indent count must be multiples of four, i.e there was a remainder or its less than 4
                    return;
                }

                if ($this->current_indent_level === $space_count) {
                    //CASE 2: current item is at the same indentation level as the prev

                    $this->markup_text
                        .= self::MARKUP_BLOCK_CHILD_CLOSE//close the prev list item </li>
                        . self::MARKUP_BLOCK_CHILD . trim($line)//add new <l>i item
                        . self::MARKUP_BLOCK_CHILD_CLOSE;; //close this </li> item

                }


                if ($space_count - $this->current_indent_level === 4) {
                    //CASE 3: current item is one level deep from the previous
                    //i.e a sub menu

                    $this->markup_text
                        .= self::MARKUP_BLOCK_START //we are one level deep, so start a new <ul>
                        . self::MARKUP_BLOCK_CHILD . trim($line) //append cuurent line as an <li>
                        . self::MARKUP_BLOCK_CHILD_CLOSE; //close the </li>

                    $this->current_indent_level = $space_count; //

                }

                if ($this->current_indent_level - $space_count === 4) {
                    //CASE 3: current item is at one indent level outside the previous
                    //i.e leaving a submenu

                    $this->markup_text
                        .= self::MARKUP_BLOCK_CLOSE //close a </ul>
                        . self::MARKUP_BLOCK_CHILD_CLOSE //close a parent </li> of that <ul>
                        . self::MARKUP_BLOCK_CHILD //open an <li> for this level
                        . trim(
                            $line //<li> content , we are not closing the <li> in case there's a child <ul> to be appended
                        );
                    $this->current_indent_level = $space_count; //cache

                }


            }

        }

        public function __destruct()
        {
            $this->data_file_handle = null; //close n cleanup
        }
    }


}

namespace { //global

    use solutionstack\TextToUL;

    new TextToUL("./input_data");

}