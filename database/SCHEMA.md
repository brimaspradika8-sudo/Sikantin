# Database Schema

## audit_logs

```sql
CREATE TABLE "audit_logs" ("id" integer primary key autoincrement not null, "actor_id" integer not null, "subject_id" integer, "action" varchar not null, "description" text not null, "ip_address" varchar, "created_at" datetime, "updated_at" datetime, foreign key("actor_id") references "users"("id") on delete cascade, foreign key("subject_id") references "users"("id") on delete set null)
```

## bank_accounts

```sql
CREATE TABLE "bank_accounts" ("id" integer primary key autoincrement not null, "user_id" integer not null, "bank_name" varchar not null, "account_number" varchar not null, "account_holder" varchar not null, "is_primary" tinyint(1) not null default '1', "is_active" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade)
```

## cart_items

```sql
CREATE TABLE "cart_items" ("id" integer primary key autoincrement not null, "cart_id" integer not null, "product_id" integer not null, "quantity" integer not null default '1', "created_at" datetime, "updated_at" datetime, foreign key("cart_id") references "carts"("id") on delete cascade, foreign key("product_id") references "products"("id") on delete cascade)
```

## carts

```sql
CREATE TABLE "carts" ("id" integer primary key autoincrement not null, "user_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade)
```

## categories

```sql
CREATE TABLE "categories" ("id" integer primary key autoincrement not null, "name" varchar not null, "slug" varchar not null, "icon" varchar, "created_at" datetime, "updated_at" datetime)
```

## menu_items

```sql
CREATE TABLE "menu_items" ("id" integer primary key autoincrement not null, "seller_id" integer not null, "category_id" integer, "name" varchar not null, "slug" varchar not null, "description" text, "price" numeric not null, "stock" integer not null default '999', "image" varchar, "is_available" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime, foreign key("seller_id") references "users"("id") on delete cascade, foreign key("category_id") references "categories"("id") on delete set null)
```

## migrations

```sql
CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null)
```

## model_has_permissions

```sql
CREATE TABLE "model_has_permissions" ("permission_id" integer not null, "model_type" varchar not null, "model_id" integer not null, foreign key("permission_id") references "permissions"("id") on delete cascade, primary key ("permission_id", "model_id", "model_type"))
```

## model_has_roles

```sql
CREATE TABLE "model_has_roles" ("role_id" integer not null, "model_type" varchar not null, "model_id" integer not null, foreign key("role_id") references "roles"("id") on delete cascade, primary key ("role_id", "model_id", "model_type"))
```

## notifications

```sql
CREATE TABLE "notifications" ("id" varchar not null, "type" varchar not null, "notifiable_type" varchar not null, "notifiable_id" integer not null, "data" text not null, "read_at" datetime, "created_at" datetime, "updated_at" datetime, primary key ("id"))
```

## order_items

```sql
CREATE TABLE "order_items" ("id" integer primary key autoincrement not null, "order_id" integer not null, "product_id" integer not null, "quantity" integer not null, "price" numeric not null, "created_at" datetime, "updated_at" datetime, foreign key("order_id") references "orders"("id") on delete cascade, foreign key("product_id") references "products"("id") on delete cascade)
```

## order_notifications

```sql
CREATE TABLE "order_notifications" ("id" integer primary key autoincrement not null, "user_id" integer not null, "order_id" integer not null, "type" varchar not null, "title" varchar not null, "message" text not null, "icon" varchar, "color" varchar, "is_read" tinyint(1) not null default '0', "read_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade, foreign key("order_id") references "orders"("id") on delete cascade)
```

## orders

```sql
CREATE TABLE "orders" ("id" integer primary key autoincrement not null, "user_id" integer not null, "seller_id" integer not null, "order_number" varchar not null, "total_amount" numeric not null, "status" varchar not null default ('pending_payment'), "payment_method" varchar, "created_at" datetime, "updated_at" datetime, "vendor_id" integer, "customer_note" text, "discount_amount" numeric not null default '0', "tax_amount" numeric not null default '0', "service_fee" numeric not null default '0', "estimated_ready_at" datetime, "pickup_window_at" datetime, foreign key("seller_id") references users("id") on delete cascade on update no action, foreign key("user_id") references users("id") on delete cascade on update no action, foreign key("vendor_id") references "vendors"("id") on delete set null)
```

## password_reset_tokens

```sql
CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"))
```

## payment_status_histories

```sql
CREATE TABLE "payment_status_histories" ("id" integer primary key autoincrement not null, "payment_id" integer not null, "actor_id" integer, "from_status" varchar, "to_status" varchar not null, "note" text, "created_at" datetime, "updated_at" datetime, foreign key("payment_id") references "payments"("id") on delete cascade, foreign key("actor_id") references "users"("id") on delete set null)
```

## payments

```sql
CREATE TABLE "payments" ("id" integer primary key autoincrement not null, "order_id" integer not null, "amount" numeric not null, "payment_status" varchar not null default ('pending'), "payment_proof" varchar, "created_at" datetime, "updated_at" datetime, "payment_channel" varchar, "transaction_id" varchar, "snap_token" varchar, "invoice_number" varchar, "bank_name" varchar, "account_number" varchar, "account_holder" varchar, "raw_response" text, "paid_at" datetime, "verified_at" datetime, "verified_by" integer, foreign key("order_id") references orders("id") on delete cascade on update no action, foreign key("verified_by") references "users"("id") on delete set null)
```

## permissions

```sql
CREATE TABLE "permissions" ("id" integer primary key autoincrement not null, "name" varchar not null, "guard_name" varchar not null, "created_at" datetime, "updated_at" datetime)
```

## pickup_qrcodes

```sql
CREATE TABLE "pickup_qrcodes" ("id" integer primary key autoincrement not null, "order_id" integer not null, "token" varchar not null, "expires_at" datetime not null, "is_used" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime, foreign key("order_id") references "orders"("id") on delete cascade)
```

## products

```sql
CREATE TABLE "products" ("id" integer primary key autoincrement not null, "user_id" integer not null, "category_id" integer, "name" varchar not null, "slug" varchar not null, "description" text, "price" numeric not null, "stock" integer not null default ('0'), "image" varchar, "created_at" datetime, "updated_at" datetime, "vendor_id" integer, "is_open" tinyint(1) not null default '1', foreign key("category_id") references categories("id") on delete set null on update no action, foreign key("user_id") references users("id") on delete cascade on update no action, foreign key("vendor_id") references "vendors"("id") on delete set null)
```

## role_has_permissions

```sql
CREATE TABLE "role_has_permissions" ("permission_id" integer not null, "role_id" integer not null, foreign key("permission_id") references "permissions"("id") on delete cascade, foreign key("role_id") references "roles"("id") on delete cascade, primary key ("permission_id", "role_id"))
```

## roles

```sql
CREATE TABLE "roles" ("id" integer primary key autoincrement not null, "name" varchar not null, "guard_name" varchar not null, "created_at" datetime, "updated_at" datetime)
```

## seller_applications

```sql
CREATE TABLE "seller_applications" ("id" integer primary key autoincrement not null, "user_id" integer not null, "business_name" varchar not null, "address" text not null, "contact" varchar not null, "product_type" varchar not null, "status" varchar check ("status" in ('pending', 'approved', 'rejected')) not null default 'pending', "seller_user_id" integer, "rejection_reason" text, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade, foreign key("seller_user_id") references "users"("id") on delete set null)
```

## users

```sql
CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "role" varchar not null default 'user', "phone" varchar, "store_name" varchar, "address" text, "status" varchar not null default 'active', "remember_token" varchar, "created_at" datetime, "updated_at" datetime, "is_closed" tinyint(1) not null default '0')
```

## vendors

```sql
CREATE TABLE "vendors" ("id" integer primary key autoincrement not null, "user_id" integer, "name" varchar not null, "slug" varchar not null, "description" text, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete set null)
```

