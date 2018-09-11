<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">订单流水号：{{ $order->no }}</h3>
    <div class="box-tools">
      <div class="btn-group pull-right" style="margin-right: 10px">
        <a href="/admin/orders" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
      </div>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered">
      <tbody>
      <tr style="background-color: #fcf8e3">
        <td>买家：</td>
        <td>{{ $order->address['contact_name'] }}</td>
        <td>下单时间：</td>
        <td colspan="2">{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
      </tr>
      <tr style="background-color: #bce8f1">
        <td>收货地址</td>
        <td colspan="4">{{ $order->address['address'] }} {{ $order->address['contact_name'] }} {{ $order->address['contact_phone'] }}</td>
      </tr>
      <tr style="background-color: #d6e9c6">
        <td rowspan="{{ $order->items->count() + 1 }}">商品列表</td>
        <td>商品Id</td>
        <td>名称</td>
        <td>单价</td>
        <td>数量</td>
      </tr>
      @foreach($order->items as $item)
      <tr style="background-color: #d6e9c6">
        <td>{{ $item->product->id }}</td>
        <td>{{ $item->product->title }}</td>
        <td>￥{{ $item->price }}</td>
        <td>{{ $item->number }}</td>
      </tr>
      @endforeach
      <tr style="background-color: #fcf8e3">
        <td>订单金额：</td>
        <td colspan="4">￥{{ $order->total_amount }}</td>
      </tr>
      <tr style="background-color: #f2dede">
        <td>备注信息：</td>
        <td colspan="4">
          {{ $order->remark }}
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
