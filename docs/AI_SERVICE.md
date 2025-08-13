# AI Service Documentation

## Overview

The AI Service provides intelligent features for the aiProject application, including product analysis, customer insights, order pattern recognition, and automated content generation.

## Features

### 1. Product Intelligence
- **Product Description Generation**: Automatically generate compelling product descriptions
- **Product Classification**: Automatically categorize and tag products
- **Smart Product Recommendations**: AI-powered product suggestions based on customer history

### 2. Customer Analytics
- **Customer Behavior Analysis**: Analyze customer purchasing patterns and preferences
- **Customer Segmentation**: Automatically classify customers into segments
- **Purchase Predictions**: Predict likely future purchases

### 3. Order Intelligence
- **Order Pattern Analysis**: Identify trends and anomalies in ordering behavior
- **Fraud Detection**: Detect unusual ordering patterns
- **Seasonal Analysis**: Identify seasonal buying patterns

### 4. Search Enhancement
- **Smart Search Suggestions**: Generate intelligent search suggestions
- **Query Expansion**: Expand search queries with related terms
- **Contextual Search**: Provide context-aware search results

## Configuration

Add these variables to your `.env` file:

```env
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=150
OPENAI_TEMPERATURE=0.7
```

## API Endpoints

All AI endpoints require authentication via Sanctum and are rate-limited.

### Service Status
```http
GET /api/ai/status
```

Returns the current status and configuration of the AI service.

### General Processing
```http
POST /api/ai/process
Content-Type: application/json

{
    "input": "Your text to process",
    "options": {
        "model": "gpt-3.5-turbo",
        "max_tokens": 200,
        "temperature": 0.7
    }
}
```

### Product Features

#### Generate Product Description
```http
POST /api/ai/products/{product_id}/description
```

#### Classify Product
```http
POST /api/ai/products/{product_id}/classify
```

### Customer Features

#### Analyze Customer
```http
POST /api/ai/customers/{customer_id}/analyze
```

#### Get Product Recommendations
```http
POST /api/ai/customers/{customer_id}/recommendations
Content-Type: application/json

{
    "limit": 5
}
```

### Order Analysis
```http
POST /api/ai/orders/analyze
Content-Type: application/json

{
    "customer_id": 123,
    "days": 90
}
```

### Search Suggestions
```http
POST /api/ai/search/suggestions
Content-Type: application/json

{
    "query": "electrical cables",
    "context": ["industrial", "commercial"]
}
```

## CLI Commands

### Test AI Service
```bash
php artisan ai:test
```

Test specific features:
```bash
php artisan ai:test --feature=status
php artisan ai:test --feature=product
php artisan ai:test --feature=customer
php artisan ai:test --feature=search
```

## Usage Examples

### Basic PHP Usage

```php
use App\Services\AiService;

$aiService = app(AiService::class);

// Basic processing
$response = $aiService->process("What's the best industrial cable?");

// Product description
$description = $aiService->generateProductDescription([
    'name' => 'Industrial Ethernet Cable',
    'sku' => 'IEC-001',
    'description' => 'Cat6 cable for industrial use'
]);

// Customer analysis
$analysis = $aiService->analyzeCustomerData([
    'customer_id' => 123,
    'name' => 'ACME Corp',
    'orders' => [...],
    'total_value' => 50000
]);
```

### JavaScript/Frontend Usage

```javascript
// Get AI service status
const status = await fetch('/api/ai/status', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
}).then(r => r.json());

// Generate product description
const description = await fetch(`/api/ai/products/${productId}/description`, {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    }
}).then(r => r.json());

// Get search suggestions
const suggestions = await fetch('/api/ai/search/suggestions', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        query: 'electrical components',
        context: ['industrial']
    })
}).then(r => r.json());
```

## Error Handling

The AI service includes comprehensive error handling:

1. **Fallback Responses**: When AI service is unavailable, fallback responses are provided
2. **Logging**: All errors are logged for debugging
3. **Graceful Degradation**: Features continue to work even when AI is unavailable
4. **Rate Limiting**: Prevents API abuse and manages costs

## Performance Considerations

1. **Caching**: Recommendations are cached for 1 hour
2. **Rate Limiting**: API calls are limited to prevent abuse
3. **Batch Processing**: Use for bulk operations when possible
4. **Timeout Handling**: Requests timeout gracefully

## Security

1. **Authentication Required**: All AI endpoints require Sanctum authentication
2. **Input Validation**: All inputs are validated before processing
3. **Data Sanitization**: User inputs are sanitized before sending to AI
4. **API Key Security**: OpenAI API keys are stored securely in environment variables

## Monitoring and Analytics

Monitor AI service usage through:
1. Laravel logs (`storage/logs/laravel.log`)
2. API rate limiting metrics
3. Response time monitoring
4. Error rate tracking

## Troubleshooting

### Common Issues

1. **"AI service unavailable"**
   - Check OPENAI_API_KEY is set
   - Verify API key is valid
   - Check internet connectivity

2. **Rate limiting errors**
   - Reduce request frequency
   - Implement client-side caching
   - Use batch processing for multiple items

3. **Timeout errors**
   - Reduce max_tokens in requests
   - Simplify input prompts
   - Check OpenAI service status

### Debug Commands

```bash
# Test AI service availability
php artisan ai:test --feature=status

# Check configuration
php artisan config:show services.openai

# View recent logs
tail -f storage/logs/laravel.log | grep "AI Service"
```

## Future Enhancements

Planned features:
1. Multi-language support
2. Custom model fine-tuning
3. Image analysis capabilities
4. Voice input/output
5. Advanced analytics dashboard
6. A/B testing for AI recommendations
