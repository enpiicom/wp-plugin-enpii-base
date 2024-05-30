# Working with Unit Tests

We use PHPUnit to perform the Unit Tests. We still use PHPUnit 9 because of several tools that only support PHPUnit 9 and this time like Mockery...

## Writing Unit Test

### Mock user functions (global functions)
We use WP_Mock to do this (WP_Mock is based on Mockery so some of the methods from Mockery can be used)

For example, we have the function `sanitize_text_field($text)` and in the testing method, it was called 2 times and we need to return the correct results for each time it called based on the argument `$text` we can do like this

```
WP_Mock::userFunction( 'sanitize_text_field' )
	->times( 2 )
	->withAnyArgs()
	->andReturnUsing(
		function ( $text ) {
			return (string) $text === 'test-01' ? 'result-01' : 'result-02';
		}
	);
```
