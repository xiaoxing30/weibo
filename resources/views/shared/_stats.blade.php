<a href="#">
  <strong id="following" class="stat">
    {{ count($user->followings) }}
  </strong>
  关注
</a>
<a href="#">
  <strong id="followers" class="stat">
    {{ count($user->followers) }}
  </strong>
  粉丝
</a>
<a href="#">
  <strong id="statuses" class="stat">
{{--
        我们通过调用 Eloquent 模型的 count 方法来获取用户发布过的微博数，
        这个做法并不算是最佳实践，因为在大型应用中，为了节省服务器资源，优化数据库查询效率，
        常会采用的方法是在数据库中添加一个模型计数器字段，在每次对模型进行创建或删除时对该字段进行更新，
        而由于本书开发的应用只是小型的演示应用，因此在这里我们使用 count 方法来查询即可。
        --}}
    {{ $user->statuses()->count() }}
  </strong>
  微博
</a>
