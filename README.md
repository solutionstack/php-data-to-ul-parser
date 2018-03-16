# php-data-to-ul-parser
Demo for parsing Structured text data to an HTML &lt;ul> 


## The PHP scripts expects structured/formatted data in the input_data file

The input file format is formatted with each level indexed by four spaces more than the level above,
Level zero (the first line) is indexed with four spaces exactly


### Example
```
    Head
    Same level
        Sublevel
            Sub-sub-level
            same-level
        Sublevel
    Same level
        Sublevel
    Same level

```

The class is automatically called, when you run the index.php file. With the input above the output should be

```html

                    <ul>
                        <li>Head</li>
                        <li>Same level</li>
                        <ul>
                            <li>Sublevel</li>
                            <ul>
                                <li>Sub-sub-level</li>
                                <li>same-level</li>
                            </ul>
                            <li>Sublevel</li>
                        </ul>
                        <li>Same level
                            <ul>
                                <li>Sublevel</li>
                            </ul>
                        </li>
                        <li>Same level</li>
                    </ul>
  
```
